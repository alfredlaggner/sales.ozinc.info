<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\AgedReceivable;

class updateArScout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:scout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('scout:flush App\\\AgedReceivable');
        Artisan::call('scout:import App\\\AgedReceivable');
        Artisan::call('scout:flush App\\\AgedReceivablesTotal');
        Artisan::call('scout:import App\\\AgedReceivablesTotal');
        $this->info(date_format(date_create(), 'Y-m-d H:i:s'));
    }
}
