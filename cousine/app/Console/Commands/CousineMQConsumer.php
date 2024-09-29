<?php

namespace App\Console\Commands;

use App\Http\Controllers\CousineController;
use App\Jobs\Cousine\CreateCousineJob;
use App\Models\Cousine;
use App\Services\rabbitmq\SendMQ;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class CousineMQConsumer extends Command
{
    protected $signature = 'mq:cousine';
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
        $this->process_store_queue('send_store_cousine_queue');
        $this->process_update_status_queue('send_update_cousine_queue');
        $this->process_get_queue('send_get_cousine_queue', 'send_get_cousine_exchange', 'cousine_key_get');
        while (self::$channel->is_consuming()) {
            self::$channel->wait();
        }
        $this->close();
    }

    public function process_store_queue($queueName)
    {
            $callback = function ($msg) {
                echo ' [x] Recibiendo: ', $msg->body, "\n";
    
                $cousine = json_decode($msg->body, true);
                try {
                    $cousineCreated = CousineController::store($cousine)->toArray();
                    echo "Cousine creado exitosamente.\n";
                    Log::info('cousinecreted', $cousineCreated);
                    $this->notifyToRecipes($cousineCreated);
                } catch (\Exception $e) {
                    echo "Error al crear Cousine: " . $e->getMessage() . "\n";
                }
            };
        
            self::$channel->queue_declare($queueName, false, false, false, false);
            self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
        
            echo 'Waiting for new messages on send_store_cousine_queue', "\n";
    }

    public function process_update_status_queue($queueName)
    {
            $callback = function ($msg) {
                echo ' [x] Recibiendo: ', $msg->body, "\n";
    
                $cousine = json_decode($msg->body, true);
                try {
                    CousineController::update(['status_id' => $cousine['status_id'], 'id' => $cousine['id']]);
                    echo "Cousine actualizado status exitosamente.\n";
                } catch (\Exception $e) {
                    echo "Error al actualizar status Cousine: " . $e->getMessage() . "\n";
                }
            };
        
            self::$channel->queue_declare($queueName, false, false, false, false);
            self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
        
            echo 'Waiting for new messages on send_update_cousine_queue', "\n";
    }


    public function notifyToRecipes($cousine)  {
        $recipeId = rand(1, 6);
        SendMQ::handle('receive_notify_recipe_queue', 'receive_notify_recipe_exchange', 'recipe_key_get', ['id' => $recipeId]);
        CousineController::update(['recipe_id' => $recipeId, 'id' => $cousine['id']]);
    }

    public function process_get_queue($queueName, $exchangeName, $routingKey)
    {
        $list =  CousineController::index();
        self::$channel->exchange_declare($exchangeName, 'direct', false, false, false);
        self::$channel->queue_declare($queueName, false, false, false, false);
        self::$channel->queue_bind($queueName, $exchangeName, $routingKey);
        $msg = new AMQPMessage(json_encode($list));
        self::$channel->basic_publish($msg, $exchangeName, $routingKey);
         echo "Cousine listado exitosamente con datos actuales en cola.\n";
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

