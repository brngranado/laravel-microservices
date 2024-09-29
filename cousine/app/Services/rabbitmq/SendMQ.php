<?php

namespace App\Services\rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendMQ
{
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

    /**
     * Envia un mensaje a la cola de mensajeria indicada.
     *
     * @param string $queueName   Nombre de la cola de mensajeria.
     * @param string $exchangeName Nombre de la exchange de mensajeria.
     * @param string $routingKey   Clave de routing para la cola especificada.
     * @param mixed  $data         Contenido del mensaje.
     */
    public static function handle($queueName, $exchangeName, $routingKey, $data)
    {
        self::connect();
        self::$channel->exchange_declare($exchangeName, 'direct', false, false, false);
        self::$channel->queue_declare($queueName, false, false, false, false);
        self::$channel->queue_bind($queueName, $exchangeName, $routingKey);
        $msg = new AMQPMessage(json_encode($data));
        self::$channel->basic_publish($msg, $exchangeName, $routingKey);

        echo "Enviado desde el SendMQ: ", json_encode($data), "\n";
    }

    /**
     * Cierra la conexi n a RabbitMQ.
     *
     * Este m todo debe ser llamado cuando se haya terminado
     * de enviar mensajes y se desee liberar los recursos
     * relacionados con la conexi n.
     */
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