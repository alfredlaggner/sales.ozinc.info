<?php

namespace App\Http\Controllers;

use App\Exports\AgedReceivableTotalExport;
use App\Exports\TenNinetyExport;
use App\Month;
use App\SalesPerson;
use App\SavedCommission;
use App\TenNinetySavedCommission;
use App\TenNinetyCalendar;
use App\TenNinetyCommission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class TenNintyCommissionController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $months = Month::where('month_id', '<=', $now->month)
            ->orderBy('month_id', 'desc')
            ->get();
        $pay_periods = TenNinetyCalendar::whereMonth('start', '<=', $now->month)
            ->orderBy('start', 'asc')
            ->get();
        $pay_calendar = [];
        foreach ($pay_periods as $pay_period) {
            array_push($pay_calendar, [
                'id' => $pay_period->id,
                'month' => $pay_period->month,
                'start' => $pay_period->start,
                'end' => $pay_period->end,
                'pay_date' => $pay_period->pay_date,
            ]);
        }

        $paidMonths = SavedCommission::where('is_commissions_paid', '>', 0)
            ->where('month', '>=', env('PAID_INVOICES_START_MONTH'))
            ->orderBy('month', 'desc')
            ->get();

        //  dd($paidMonths);

        $data = ['id' => 0, 'month' => $now->month];

        return view('ten_ninety.main', ['data' => $data, 'pay_periods' => $pay_calendar, 'year' => $now->year]);
    }

    public function enter_month_schema(Request $request)
    {
        $now = Carbon::now();
        $current_month = $now->month;
        $current_year = $now->year;

        $year = $request->get('year');
        $pay_period = TenNinetyCalendar::find($request->get('pay_period'));
        $month = $pay_period->month;
        $half = $pay_period->half;


        $reps = SalesPerson::where('is_ten_ninety', True)->get();
        $rep_count = $reps->count();


        //   dd($rep_count);
        /*        echo $year . '<br>';
                echo $month . '<br>';
                echo $half . '<br>';*/
        $ten_ninety_goals = TenNinetyCommission::select('*', 'sales_persons.name as rep_name')
            ->where('year', $year)
            ->where('month', $month)
            ->where('half', $half)
            ->where('volume', ">", 1)
            ->where('goal', ">", 0)
            ->orderby('rep_name', 'asc')
            ->leftJoin('sales_persons', 'sales_persons.sales_person_id', '=', 'ten_ninety_commissions.rep_id')
            ->get();
        /*        echo $current_year;
               echo $current_month; */
        //     dd( $ten_ninety_goals);

        if ($request->get('display') == 'create') {
            return view('ten_ninety.create', compact('reps', 'rep_count', 'month', 'year', 'half', 'ten_ninety_goals'));
        } elseif ($request->get('display') == 'list') {
            //     dd($month);
            session(['commission_month' => $month]);
            session(['commission_half' => $half]);
            session(['commission_year' => $year]);
            //      $value = $request->session()->get('commission_month');
            return view('ten_ninety.list', compact('reps', 'rep_count', 'month', 'year', 'half', 'ten_ninety_goals'));
        } elseif ($request->get('display') == 'edit') {
            return view('ten_ninety.edit', compact('reps', 'rep_count', 'month', 'year', 'ten_ninety_goals'));
        }
    }

    public function list(Request $request)
    {

    }

    public function create(Request $request)
    {
        //  dd($request);
        $reps = SalesPerson::where('is_salesperson', True)->get();
        $month = $request->get('month');
        $half = $request->get('half');
        $year = $request->get('year');
//dd($request);
        foreach ($reps as $rep) {
            $rep_id_name = 'rep_id_' . $rep->sales_person_id;
            $expected_volume_name = 'expected_volume_' . $rep->sales_person_id;
            $expected_volume = 'expected_volume_' . $rep->sales_person_id;
            $rep_id = $request->get($rep_id_name);
            $expected_volume = $request->get($expected_volume_name);
            TenNinetyCommission::updateOrCreate(
                [
                    'month' => $month,
                    'half' => $half,
                    'year' => $year,
                    'rep_id' => $rep_id
                ],
                [
                    'goal' => $expected_volume,
                    'is_ten_ninety' => $rep->is_ten_ninety,
                ]);
        }
        $this->update_collected($month, $year, $half);
        return redirect()->route('ten_ninty_main');
    }

    function update_collected($month = 10, $year = 2019, $half = 1)
    {

        $savedCommission = TenNinetySavedCommission::where('year', $year)->where('month', $month)->where('half', $half)->first();
        if (!$savedCommission) {
            abort(403, 'Month does not exist: ' . $month . ' ' . $year);
        }
        $queries = DB::table($savedCommission->name)->select(DB::raw('*,
                sum(commission) as sp_commission,
                sum(amount) as sp_volume,
                EXTRACT(YEAR_MONTH FROM ' . $savedCommission->name . '.invoice_date) as summary_year_month
                '))
            //			->leftJoin('sales_persons', 'sales_persons.sales_person_id', '=', $savedCommission->name . '.sales_person_id')
            //			->where('sales_persons.region', '!=', null)
            ->orderBy('rep')
            ->groupBy('rep')
            ->get();

        foreach ($queries as $query) {
            TenNinetyCommission::where('month', $month)
                ->where('year', $year)
                ->where('rep_id', $query->sales_person_id)
                ->update(
                    [
                        'volume_collected' => $query->sp_volume
                    ]);
        }
    }

    public
    function update(Request $request)
    {
        $reps = SalesPerson::where('is_salesperson', True)->get();
//dd($request);
        foreach ($reps as $rep) {
            $rep_id_name = 'rep_id_' . $rep->sales_person_id;
            $expected_volume_name = 'expected_volume_' . $rep->sales_person_id;
            $expected_volume = 'expected_volume_' . $rep->sales_person_id;
            $rep_id = $request->get($rep_id_name);
            $expected_volume = $request->get($expected_volume_name);

            TenNinetyCommission::where('month', $request->get('month'))
                ->where('year', $request->get('year'))
                ->where('rep_id', $rep_id)
                ->update(
                    [
                        'goal' => $expected_volume
                    ]);
        }
        return redirect()->route('ten_ninty_main');
    }

    public function export_commissions(Request $request)
    {
        $month = ($request->session()->get('commission_month'));
        $half = ($request->session()->get('commission_half'));
        $year = ($request->session()->get('commission_year'));
        return Excel::download(new TenNinetyExport([$month, $half, $year]), 'ten_ninety_commissions.xlsx');
    }

}
