<?php

namespace App\Jobs\Cousine;

use App\Http\Controllers\CousineController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
        Log::info('Datos recibidos en el job cousine :', $this->data);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        CousineController::store($this->data);
    }
}
