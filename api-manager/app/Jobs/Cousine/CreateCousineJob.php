<?php

namespace App\Jobs\Cousine;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CreateCousineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        Log::info('Datos recibidos en el job:', $this->data);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('AMQP_HOST', 'localhost'),
            env('AMQP_PORT', 5672),
            env('AMQP_USER', 'bryan'),
            env('AMQP_PASSWORD', '123456'),
            env('AMQP_VHOST', '/')
        );
    
        $channel = $connection->channel();
        $channel->exchange_declare('send_store_cousine', 'direct', false, false, false);
        $channel->queue_declare('send_store_cousine', false, true, false, false);
    
        // Enviar solo los datos necesarios
        $messageData = [
            'order_number' => 222222,
            // Puedes agregar más campos aquí si es necesario
        ];
        
        $msg = new AMQPMessage(json_encode($messageData)); // Enviamos solo los datos
        $channel->basic_publish($msg, 'send_store_cousine', 'send_store_cousine');
    
        echo "SE ENVIA A RABBIT DESDE API MANAGER: " . json_encode($messageData) . "\n";
    
        $channel->close();
        $connection->close();
    }
}


