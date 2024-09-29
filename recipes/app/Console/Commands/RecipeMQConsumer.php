<?php

namespace App\Console\Commands;

use App\Http\Controllers\CousineController;
use App\Http\Controllers\RecipeController;
use App\Jobs\Cousine\CreateCousineJob;
use App\Models\Cousine;
use App\Services\rabbitmq\SendMQ;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RecipeMQConsumer extends Command
{
    protected $signature = 'mq:recipe';
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
        $this->process_notification_queue('receive_notify_recipe_queue');
        while (self::$channel->is_consuming()) {
            self::$channel->wait();
        }
        $this->close();
    }

    public function process_notification_queue($queueName){
        $callback = function ($msg) {
            echo ' [x] Recibiendo: ', $msg->body, "\n";

            $recipeId = json_decode($msg->body, true);

            try {
                $dataIngredients = RecipeController::findRecipeWithGroseryStore($recipeId['id']);
                Log::info('Recipe with ingredients: '.$dataIngredients);
                $this->notifyToGroceryStore($dataIngredients);
                
                echo "Recipe obtenido exitosamente.\n";
            } catch (\Exception $e) {
                echo "Error al obtener recipe: " . $e->getMessage() . "\n";
            }
        };
    
        self::$channel->queue_declare($queueName, false, false, false, false);
        self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
    
        echo 'Waiting for new messages on receive_notify_recipe_queue', "\n";
    }

    public function notifyToGroceryStore($recipe)  {
        $ingredients = $recipe['ingredients'];
        SendMQ::handle('receive_notify_grocery_queue', 'receive_notify_grocery_exchange', 'grocery_key_get', ['ingredients' => $ingredients]);

    }

    public function process_store_queue($queueName)
    {
            $callback = function ($msg) {
                echo ' [x] Recibiendo: ', $msg->body, "\n";
    
                $cousine = json_decode($msg->body, true);
                try {
        RecipeController::store($cousine);
                    echo "Cousine creado exitosamente.\n";
                } catch (\Exception $e) {
                    echo "Error al crear Cousine: " . $e->getMessage() . "\n";
                }
            };
        
            self::$channel->queue_declare($queueName, false, false, false, false);
            self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
        
            echo 'Waiting for new messages on send_store_cousine_queue', "\n";
    }

   

    public function process_update_queue($queueName)
    {
            $callback = function ($msg) {
                echo ' [x] Recibiendo: ', $msg->body, "\n";
    
                $recipe = json_decode($msg->body, true);
                try {
                    RecipeController::update($recipe['id'], $recipe);
                    echo "recipe actualizado exitosamente.\n";
                } catch (\Exception $e) {
                    echo "Error al crear recipe: " . $e->getMessage() . "\n";
                }
            };
        
            self::$channel->queue_declare($queueName, false, false, false, false);
            self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
        
            echo 'Waiting for new messages on send_update_cousine_queue', "\n";
    }

    public function process_get_queue($queueName, $exchangeName, $routingKey)
    {
        $list =  new RecipeController();
        $list =  $list->index();
        self::$channel->exchange_declare($exchangeName, 'direct', false, false, false);
        self::$channel->queue_declare($queueName, false, false, false, false);
        self::$channel->queue_bind($queueName, $exchangeName, $routingKey);
        $msg = new AMQPMessage(json_encode($list));
        self::$channel->basic_publish($msg, $exchangeName, $routingKey);
         echo "Cousine listado exitosamente con datos actuales en cola.\n";
            // $callback = function ($msg) use ($queueName, $exchangeName, $routingKey) {
            //     echo ' [x] Recibiendo: ', $msg->body, "\n";
    
            //     $cousine = json_decode($msg->body, true);
            //     try {
                  
            //     } catch (\Exception $e) {
            //         echo "Error al crear Cousine: " . $e->getMessage() . "\n";
            //     }
            // };
        
            // self::$channel->queue_declare($queueName, false, false, false, false);
            // self::$channel->basic_consume($queueName, '', false, true, false, false, $callback);
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

