<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Support\Facades\Http;

class TriggerMakeScenario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trigger-make-scenario';

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
        $webhookUrl = 'hook.eu2.make.com/gpr1ndahi5c6u5vjkoxxvq7iodsaia4b';
        $response = null;
        try{
            $response = Http::retry(3,100)->post($webhookUrl);
        }catch(Exception $e){
            dd($e);
        }

        if ($response->successful()) {
            $this->info('Make.com scenario triggered successfully!');
        } else {
            $this->error('Failed to trigger the Make.com scenario: ' . $response->body());
        }

        return 0;
    }
}
