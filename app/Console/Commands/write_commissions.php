<?php

namespace App\Console\Commands;

use App\Payment;
use App\TestHorizon;
use Edujugon\Laradoo\Odoo;
use Illuminate\Console\Command;

class write_commissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'odoo:writeCommissions {payments}{--queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Write commissions to odoo invoice model';

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
     * @return int
     */
    public function handle()
    {

        $payments = $this->argument('payments');
        $queueName = $this->option('queue');

        $counter = $payments->count();

        $odoo = new Odoo();
        $odoo->connect();

        TestHorizon::truncate();
        $iters = 0;
        foreach ($payments as $payment) {

            if ($iters==10) die();

            $this->info($counter--);

            TestHorizon::updateOrCreate([
                'invoice_id' => $payment->invoice_id,
                'commission' => $payment->commission,
                'percent' => $payment->comm_percent * 100,
            ]);
            $iters++;

            $odoo->where('id', $payment->invoice_id)
                ->update('account.invoice', [
                    'x_studio_commission' => $payment->commission,
                    'x_studio_commission_percent' => $payment->comm_percent * 100,
                ]);
        }

        return 0;
    }
}
