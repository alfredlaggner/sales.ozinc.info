<?php

namespace App\Http\Controllers;

use App\EmployeeBonus;
use App\Payment;
use App\SalesPerson;
use App\TenNinetyCalendar;
use App\TenNinetySavedCommission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenNinetyController extends Controller
{
    public function bonus_init(Request $request, $year = 0, $month = 0, $half = 0)
    {

        $calendar_id = $request->get('month_id');
        $calendar = TenNinetyCalendar::where('id', $calendar_id)->first();
        if (!$year) {
            $month = $calendar->month;
            $year = substr($calendar->pay_date, 0, 4);
            $half = $calendar->half;
        }
        //   if (Carbon::now()->month - $month == 1) {
        if (true) {
            $read_only = '';
            $submit = "submit";
        } else {
            $read_only = 'readonly';
            $submit = "button";
        }
        $sps = SalesPerson::whereIsTenNinety(true)->get();
        foreach ($sps as $sp) {
            EmployeeBonus::updateOrCreate([
                'sales_person_id' => $sp->sales_person_id,
                'year' => $year,
                'month' => $month,
                'half' => $half,
            ], [
                'sales_person_name' => $sp->name,
                'is_ten_ninety' => true,
                'base_bonus' => 0.06,
            ]);
        }
        $monthNum = $month;
        $dateObj = DateTime::createFromFormat('!m', $monthNum);
        $month_name = $dateObj->format('F'); // March

        $bonuses = EmployeeBonus::where('year', $year)->where('month', $month)->where('half', $half)->whereIsTenNinety(true)->get();
      //     dd($bonuses);
        return view('ten_ninety.bonus_init', compact('sps', 'bonuses', 'year', 'month', 'half', 'month_name', 'read_only', 'submit', 'calendar_id'));
    }

    public function update(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $half = $request->get('half');
        //  dd($half);
        $percent = $request->get('percent');
        $bonus_id = $request->get('bonus_id');
        $calendar_id = $request->get('calendar_id');
        //   dd($calendar_id);
        for ($i = 0; $i < count($percent); $i++) {
            //      echo 'bonus = ' . $percent[$i] . "i= " . $i . "<br>";
            EmployeeBonus::find($bonus_id[$i])->update([
                'bonus' => $percent[$i] / 100,
            ]);
        }
        $calendar = TenNinetyCalendar::where('id', $calendar_id)->first();
        /*        echo $calendar_id;
                dd($calendar);*/
        $payments_from = $calendar->start;
        $payments_to = $calendar->end;

        $current_month = $month;
        $current_year = $year;

        $sps = SalesPerson::where('is_ten_ninety', true)->get();
        foreach ($sps as $sp) {
            EmployeeBonus::updateOrCreate(
                [
                    'sales_person_id' => $sp->sales_person_id,
                    'month' => $current_month,
                    'year' => $current_year,
                    'half' => $half,
                ],
                [
                    'sales_person_name' => $sp->name,
                    'is_ten_ninety' => true,
                ]
            );
        }
        $payments = Payment::whereNotNull('invoice_date')
            ->whereBetween('payment_date', [$payments_from, $payments_to])
                ->orderBy('payment_date','desc')
            ->get();
//dd($payments->where('sales_person_id',71)->where('sales_order','like','SO10697')->toArray());

        foreach ($payments as $payment) {
            $bonus = EmployeeBonus::where('month', $payment->month_paid)
                ->where('year', $payment->year_paid)
                ->where('sales_person_id', $payment->sales_person_id)
                ->first();
//if($payment->payment_id == 704) dd($bonus);

            if ($bonus) {
                if ($bonus->bonus > 0) {
                    $bonus_percent = $bonus->bonus;
                } else {
                    $bonus_percent = $bonus->base_bonus;
                }
/*                if($payment->id == 359){
                    echo $bonus_percent . "<br>";
                    echo $payment->amount . "<br>";
                    echo $bonus_percent * $payment->amount  . "<br>";

                    die();
                }*/
//;
//               $payment->id;
                Payment::where('id', $payment->id)
                    ->update([
                        'comm_percent' => $bonus_percent,
                        'commission' => $bonus_percent * $payment->amount,
                        'half' => $half,
                        'is_ten_ninety' => true,
                    ]);
            }
        }
        return redirect(route('1099_init', ['year' => $year, 'month' => $month, 'half' => $half]))->with('status', "All bonuses updated");
    }

    public function display_by_salesperson(Request $request)
    {

        $sales_person_id = $request->get('sales_person_id');
        $sales_person_id = 86; // Se

        $commissions = TenNinetySavedCommission::where('is_commissions_paid');
        foreach ($commissions as $commission){
            if (Schema::hasTable($commission->name)) {
                $table_name = $commission->name;
                $paid_commissions = DB::table($table_name)->
                where('sales_person_id', $sales_person_id)->get();
                foreach ($paid_commissions as $pc) {
                    echo $pc->rep . ' = ' . $pc->commission . '<br>';
                }
            }
        }
    }
}
