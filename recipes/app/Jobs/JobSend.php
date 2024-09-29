<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class JobSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        // Conectar a RabbitMQ
        $connection = new AMQPStreamConnection(
            env('AMQP_HOST', 'localhost'),
            env('AMQP_PORT', 5672),
            env('AMQP_USER', ''),
            env('AMQP_PASSWORD', ''),
            env('AMQP_VHOST', '/'),
            false,
            'AMQPLAIN',
            null,
            'en_US',
            //30 // Intervalo de heartbeat en segundos
        );

        $channel = $connection->channel();

        // Declarar la cola (asegúrate de que la cola ya esté creada)
        $channel->queue_declare('send_data_store', false, true, false, false, false, []);

        // Crear el mensaje
        $msg = new AMQPMessage(json_encode($this->data));

        // Publicar el mensaje en la cola
        $channel->basic_publish($msg, '', 'send_data_store');

        echo " [x] Mensaje enviado: ", json_encode($this->data), "\n";

        // Cerrar el canal y la conexión
        $channel->close();
        $connection->close();
    }
}