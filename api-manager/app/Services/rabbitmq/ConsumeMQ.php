<?php

namespace App\Services\rabbitmq;

use App\Http\Controllers\CousineController;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeMQ
{
    protected static $connection;
    protected static $channel;
    protected static $receivedData = []; // Variable estática para almacenar datos

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

    public static function handle($queueName)
    {
        self::connect();

        // Declarar la cola
        self::$channel->queue_declare($queueName, false, false, false, false);

        // Callback para manejar los mensajes recibidos
        $msgCallback = function (AMQPMessage $msg) {
            Log::info(' [x] Recibiendo: ' . $msg->body);
            
            // Almacenar el mensaje recibido
            $list = json_decode($msg->body, true);
            CousineController::setDataList($list);
        };

        // Configurar el consumidor
        self::$channel->basic_consume($queueName, '', false, true, false, false, $msgCallback);

        // Mantener el consumidor activo hasta que se detenga
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        while (self::$channel->is_consuming()) {
            self::$channel->wait();
        }

        return self::$receivedData; // Retornar los datos recibidos
    }

    public static function getReceivedData()
{
    return self::$receivedData;
}

    public static function close()
    {
        if (self::$channel) {
            self::$channel->close();
        }
        if (self::$connection) {
            self::$connection->close();
        }
    }
}