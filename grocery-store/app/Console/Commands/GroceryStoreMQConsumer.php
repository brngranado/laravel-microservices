<?php

namespace App\Console\Commands;

use App\Http\Controllers\GroseryController;
use App\Http\Controllers\GroseryStoreController;
use App\Jobs\Cousine\CreateCousineJob;
use App\Models\Cousine;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class GroceryStoreMQConsumer extends Command
{
    protected $signature = 'mq:grocery';
    protected $description = 'Consume messages from RabbitMQ';
    protected static $connection;
    protected static $channel;

    /**
     * Establece la conexión a RabbitMQ y declara el canal de conexión.
     *
     * Si ya existe una conexión, no hace nada.
     *
     * @return void
     */
    protected static function connect()
    {
        if (is_null(self::$connection)) {
            self::$connection = new AMQPStreamConnection(
                env('AMQP_HOST', 'localhost'),
                env('AMQP_PORT', 5672),
                env('AMQP_USER', 'bryan'),
                env('AMQP_PASSWORD', '123456'),
                env('AMQP_VHOST', '/')
            );
            self::$channel = self::$connection->channel();
        }
    }
    public function handle()
    {
        self::connect();
        $this->process_notification_queue('receive_notify_grocery_queue');

        while (self::$channel->is_consuming()) {
            self::$channel->wait();
        }
        $this->close();
    }

 

    public function process_notification_queue($queueName){
        $callback = function ($msg) {
            echo ' [x] Recibiendo: ', $msg->body, "\n";

            $ingredients = json_decode($msg->body, true);

            try {
            GroseryStoreController::store($ingredients);
                echo "Desconto los ingredientes exitosamente.\n";
            } catch (\Exception $e) {
                echo "Error al obtener recipe: " . $e->getMessage() . "\n";
            }
        };
    
        self::$channel->queue_declare($queueName, false, false, false, false);
        self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
    
        echo 'Waiting for new messages on receive_notify_grocery_queue', "\n";
    }

    /**
     * Cierra la conexi n a RabbitMQ.
     *
     * Este m todo debe ser llamado cuando se haya terminado
     * de enviar mensajes y se desee liberar los recursos
     * relacionados con la conexi n.
     */
    public function close()
    {
        if (self::$channel) {
            self::$channel->close();
        }
        if (self::$connection) {
            self::$connection->close();
        }
    }


}

