<?php

namespace App\Console\Commands;

use App\Services\rabbitmq\ConsumeMQ;
use Illuminate\Console\Command;

class CousineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cousine-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ConsumeMQ::handle('send_get_cousine_queue');

    }
}
