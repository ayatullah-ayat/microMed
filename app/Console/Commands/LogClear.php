<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LogClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    protected $signature = 'log:clear';

    // Add description to your command
    protected $description = 'Clear Laravel log';

    // Create your own custom command
    public function handle()
    {
        exec('echo "" > ' . storage_path('logs/laravel.log'));
        $this->info('Logs have been cleared');
    }

}
