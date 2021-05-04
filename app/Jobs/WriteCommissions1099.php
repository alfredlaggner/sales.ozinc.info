<?php

namespace App\Jobs;

use App\TestHorizon;
use App\Payment;
use Edujugon\Laradoo\Odoo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WriteCommissions1099 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $payments = $this->payments;
        $odoo = new Odoo();
        $odoo->connect();


        for ($i = 0; $i < count($payments); $i++) {
/*                        TestHorizon::updateOrCreate([
                            'sales_order' => $payments[$i]->sales_order,
                            'invoice_id' => $payments[$i]->invoice_id,
                            'commission' => $payments[$i]->commission,
                            'sales_person_id' => $payments[$i]->rep_id,
                        ]);
*/
            if ($payments[$i]->comm_paid_at != NULL) {
                $comm_paid_at = $payments[$i]->comm_paid_at;
            } else {
                $comm_paid_at = '2000-01-01';
            }
            $odoo->where('id', '=', $payments[$i]->invoice_id)
                ->update('account.invoice', [
                    'x_studio_commission' => $payments[$i]->commission,
                    'x_studio_commission_percent' => $payments[$i]->comm_percent * 100,
                    'x_studio_commission_paid' => $comm_paid_at
                ]);
        }

        return 0;
    }
}
