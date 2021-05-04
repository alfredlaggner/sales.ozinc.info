<?php

namespace App\Http\Controllers;

use App\EmployeeBonus;
use App\Payment;
use App\TenNinetyCommissionSalesOrder;
use App\TenNinetyPaid;
use App\InvoiceLine;
use App\Salesline;
use App\SalesPerson;
use App\TenNinetySavedCommission;
use App\TenNinetyCalendar;
use Carbon\Carbon;
use DateTime;
use Auth;
use App\Jobs\WriteCommissions1099;
use Edujugon\Laradoo\Odoo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenNinetyPaidController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function admin()
    {
        $commissions = TenNinetySavedCommission::all();
        foreach ($commissions as $commission)
            if (!Schema::hasTable($commission->name)) {
                $savedCommission = TenNinetySavedCommission::find($commission->id);
                $savedCommission->delete();
            }
        $now = Carbon::now();
        $pay_periods = TenNinetyCalendar::get();
        $pay_calendar = [];
        foreach ($pay_periods as $pay_period) {
            $month_name = (\DateTime::createFromFormat('!m', $pay_period->month))->format('F');

            array_push($pay_calendar, [
                'id' => $pay_period->id,
                'month' => $pay_period->month,
                'month_name' => $month_name,
                'start' => $pay_period->start,
                'end' => $pay_period->end,
                'half' => $pay_period->half,
                'pay_date' => $pay_period->pay_date,
            ]);

        }
        //  dd ($pay_calendar);
        $commissions = TenNinetySavedCommission::orderBy('updated_at', 'desc')->get();
   //     dd($commissions);
        $data = ['id' => 0, 'month' => $now->month];
        return view('ten_ninety.admin', ['jcommissions' => json_encode($commissions), 'commissions' => $commissions, 'data' => $data, 'data' => $data, 'pay_periods' => $pay_calendar, 'year' => $now->year]);
    }


    public function createSavedCommission(Request $request)
    {
        //  dd( $request->get('pay_period'));

        env('PAID_INVOICES_START_DATE', '2019-06-01');

        $pay_period = TenNinetyCalendar::find($request->get('pay_period'));
        $month = $pay_period->month;
        $year = '20' . $pay_period->year;
        $half = $pay_period->half;
        $start = $pay_period->start;
        $end = $pay_period->end;

        $now = Carbon::now('America/Los_Angeles');
        $currentTime = $now->format('his');
        $currentDate = $now->format('Y-m-d');
        //   dd($currentDate);
        //  dd($dateTo);

        $payments = Payment::select(DB::raw('*,
                invoices.sales_order,
                invoices.sales_person_id as rep_id,
                sales_persons.is_ten_ninety as is_rep_id_ten_ninety,
                sales_persons.name as sales_persons_name,
                invoices.state as invoices_state,
                invoices.invoice_date as invoices_invoice_date
                '))
            ->leftJoin('invoices', 'invoices.ext_id', '=', 'payments.invoice_id')
            ->leftJoin('sales_persons', 'payments.sales_person_id', '=', 'sales_persons.sales_person_id')
            ->whereBetween('payments.payment_date', [$start, $end])
            ->where('sales_persons.is_ten_ninety', '=', 1)
            ->where('payments.invoice_state', '=', 'paid')
            ->orderBy('payments.payment_date')
            ->get();

        $newtable = "1099_paid_" . $year . '_' . $month . '_pp' . $half . '_' . $currentTime;

        $statement = 'create table ' . $newtable . ' LIKE ten_ninety_commission_sales_orders';
        DB::statement($statement);
        //     DB::table($newtable)->insert($half_month);
        foreach ($payments as $payment) {
            $order_month = substr($payment->payment_date, 5, 2);
            $order_year = substr($payment->payment_date, 0, 4);

            $commission = $payment->commission;
            $amount_due = 0.00;

            /*            if ($payment->amount_due > 1.00) {
                            $commission = 0.00;
                            $amount_due = $payment->amount_due;

                        } else {
                            $amount_due = 0.00;*/
            //         $commission = $payment->amount * env('1999_BASE_BONUS');
            //}
            //   if($payment->sales_order == 'SO10669')  dd($payment->amount_due);

            //         $order_day = substr($payment->invoice_date, 8, 2);
            //        $this->info($order_day);
            DB::table($newtable)->insert(
                [
                    'month' => $order_month,
                    'display_name' => $payment->display_name,
                    'move_name' => $payment->move_name,
                    'year' => $order_year,
                    'half' => $half,
                    'rep_id' => $payment->rep_id,
                    'sales_order_id' => $payment->ext_id,
                    'customer_id' => $payment->customer_id,
                    'customer_name' => $payment->customer_name,
                    'amount' => $payment->amount,
                    'amount_untaxed' => $payment->amount,
                    'amount_taxed' => $payment->amount_taxed,
                    'payment_date' => $payment->payment_date,
                    'invoice_id' => $payment->invoice_id,
                    'invoice_date' => $payment->invoices_invoice_date,
                    'sales_order' => $payment->sales_order,
                    'commission' => $commission,
                    'comm_percent' => $payment->comm_percent,
                    'is_ten_ninety' => $payment->is_rep_id_ten_ninety ? 1 : 0,
                    'rep' => $payment->sales_persons_name,
                    'amount_due' => $amount_due,
                ]
            );
        }
        $sc = new TenNinetySavedCommission;
        $sc->description = "add description";
        $sc->name = $newtable;
        $sc->date_created = $currentDate;
        $sc->month = $month;
        $sc->half = $half;
        $sc->start = $start;
        $sc->end = $end;
        $sc->year = $request->get('year');
        $sc->created_by = Auth::user()->name;
        $sc->save();

        session(['commissions_paid_table_name' => $newtable]);

        return redirect()->route('admin_1099');
    }

    public function removeSavedCommission(Request $request, $saved_commissions_id, $table_name)
    {
        $tnc = TenNinetySavedCommission::find($saved_commissions_id);

        if (!$tnc->is_commissions_paid) {
            TenNinetySavedCommission::find($saved_commissions_id)
                ->delete();

            $newtable = $table_name;
            Schema::dropIfExists($newtable);
        }
        return redirect()->route('admin_1099');
    }

    public function paySavedCommission(Request $request, $saved_commissions_id, $table_name)
    {
        TenNinetySavedCommission::find($saved_commissions_id)
            ->update(['is_commissions_paid' => true]);

        $newtable = $table_name;

        DB::table($newtable)->update([
            'comm_paid_at' => Carbon::now()->format('Y-m-d'),
            'is_comm_paid' => true,
        ]);

        $paid_commissions = DB::table($newtable)->get();
        foreach ($paid_commissions as $paid_commission) {
            //      dd($paid_commission);

            TenNinetyPaid::where('ext_id', $paid_commission->sales_order_id)
                //          ->where('is_paid', 0)
                ->update([
                    'saved_commissions_id' => $saved_commissions_id,
                    'is_paid' => 1,
                    'paid_at' => now(),
                    'paid_by' => Auth::user()->name,
                ]);
            Payment::where('invoice_id', $paid_commission->invoice_id)
                ->where('is_comm_paid', false)
                ->update([
                    'comm_paid_at' => Carbon::now()->format('Y-m-d'),
                    'is_comm_paid' => true,
                ]);
        }
        //     $this->write_to_odoo($paid_commissions);
        $xx = $paid_commissions->toArray();
        WriteCommissions1099::dispatch($xx);

        foreach ($paid_commissions as $paid_commission) {


            TenNinetyCommissionSalesOrder::where('sales_order_id', $paid_commission->sales_order_id)
                ->update([
                    'saved_commissions_id' => $saved_commissions_id,
                    'is_comm_paid' => True,
                    'comm_paid_at' => Carbon::now()->format('Y-m-d'),
                ]);

        }
        return redirect()->route('admin_1099');
    }

    /*    public function write_to_odoo($payments)
        {
                WriteCommissions1099::dispatch($payments);

        }*/

    public
    function editSavedCommission(Request $request, $id)
    {
        $saved_commission = TenNinetySavedCommission::find($id);


        return (view('ten_ninety.edit_saved_commission', compact('saved_commission')));
    }

    public function saveSavedCommission(Request $request)
    {
        $saved_commission = TenNinetySavedCommission::find($request->get('id'));
        $saved_commission->description = $request->get('description');
        $saved_commission->comment = $request->get('comment');
        $saved_commission->save();

        return redirect()->route('admin_1099');
    }

    public function viewSavedCommission(Request $request, $id)
    {
        //	$name = $request->get('saved_name');
        $data = [];
        $line = [];
        $saved1099Commission = TenNinetySavedCommission::find($id);
        $year = $saved1099Commission->year;
        $month = $saved1099Commission->month;
        $half = $saved1099Commission->half;

//dd($saved1099Commission);
        if (!Schema::hasTable($saved1099Commission->name)) {
            return (view('nodata'));
        } else {
            $sales = DB::table($saved1099Commission->name)->select(DB::raw('*,
              sales_persons.name as rep
              '))
                ->leftJoin('sales_persons', 'sales_persons.sales_person_id', '=', $saved1099Commission->name . '.rep_id')
                ->orderBy('sales_order_id', 'desc')
                ->get();
        }

        /*echo $saved1099Commission->name;
                        dd($sales->toArray());*/

        if (!Schema::hasTable($saved1099Commission->name)) {
            return (view('nodata'));
        } else {
            $totals = DB::table($saved1099Commission->name)->select(DB::raw('*,
            sum(amount_untaxed) as amount_total,
            sum(commission) as commission_total
                '))
                ->groupBy($saved1099Commission->name . '.rep_id')
                ->orderBy($saved1099Commission->name . '.rep_id')
                ->get();
        }

//dd( json_encode($sales));
        return (view('tables.total_salesorders_1099', ['data' => json_encode($data), 'header' => $saved1099Commission, 'sales' => json_encode($sales), 'overview' => json_encode($totals)]));
    }

    public
    function view_paid_unpaid_commissions()
    {
        return view('view_paid_unpaid', [
            'today' => Carbon::now()->today()->format("Y-m-d"),
            'salesperson' => Salesperson::all(),
        ]);
    }

    public
    function list_paid_unpaid_commissions(Request $request)
    {
        $paidCommissionDateFrom = env('PAID_INVOICES_START_DATE', '2019-06-01');
        $dateTo = $request->get('today');
        $rep_id = $request->get('salesperson_id');
        $agent_name = SalesPerson::where('sales_person_id', $rep_id)->first();
//dd($agent_name->name);

        $paid_subtotals_so = $this->paid_subtotals_so($rep_id, $paidCommissionDateFrom, $dateTo);
        $paid_subtotals_month = $this->paid_subtotals_month($rep_id, $paidCommissionDateFrom, $dateTo);
        $unpaid_subtotals_so = $this->unpaid_subtotals_so($rep_id, $paidCommissionDateFrom, $dateTo);
        $unpaid_subtotals_month = $this->unpaid_subtotals_month($rep_id, $paidCommissionDateFrom, $dateTo);
        //  dd( $unpaid_subtotals_month);
        $data = [];
        $commissions_paids = Salesline::select(DB::raw('*,
                EXTRACT(YEAR_MONTH FROM invoice_date) as summary_year_month
                '))
            ->orderBy('summary_year_month', 'desc')
            ->orderBy('order_number')
            ->where('sales_person_id', '=', $rep_id)
            ->where('state', 'like', 'paid')
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('commission_paid_at', '!=', NULL)
            ->get();

        $paids = [];
        foreach ($commissions_paids as $query) {
            $month = date("F", mktime(0, 0, 0, substr($query->summary_year_month, 4, 2), 1));

            array_push($paids, [
                'month' => $month . ' ' . substr($query->summary_year_month, 0, 4),
                'invoice_date' => $query->invoice_date,
                'order_number' => $query->order_number,
                'name' => $query->name,
                'quantity' => $query->quantity,
                'commission' => $query->commission,
                'unit_price' => $query->unit_price,
                'amount' => $query->amount,
            ]);
        }
        //  dd($commissions_paids);

        $commissions_unpaids = Salesline::select(DB::raw('*,
                EXTRACT(YEAR_MONTH FROM invoice_date) as summary_year_month
                '))
            ->orderBy('summary_year_month', 'desc')
            ->orderBy('order_number', 'desc')
            ->where('sales_person_id', '=', $rep_id)
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('state', 'like', 'paid')
            ->where('invoice_paid_at', '!=', NULL)
            ->where('commission_paid_at', '=', NULL)
            ->get();

//dd($commissions_unpaids->count());

        $unpaids = [];
        foreach ($commissions_unpaids as $query) {
            $month = date("F", mktime(0, 0, 0, substr($query->summary_year_month, 4, 2), 1));
            array_push($unpaids, [
                'month' => $month . ' ' . substr($query->summary_year_month, 0, 4),
                'invoice_date' => $query->invoice_date,
                'order_number' => $query->order_number,
                'name' => $query->name,
                'quantity' => $query->quantity,
                'commission' => $query->commission,
                'unit_price' => $query->unit_price,
                'amount' => $query->amount,
            ]);
        }
        return (view('paid_unpaid', ['name' => $agent_name->name, 'paid' => json_encode($paids), 'unpaid' => json_encode($unpaids)]));


        //  return view('paid_unpaid', compact('commissions_paids', 'commissions_unpaids'));
    }

    public
    function list_paid_unpaid_commissions_work(Request $request)
    {
        $paidCommissionDateFrom = env('PAID_INVOICES_START_DATE', '2019-06-01');
        $dateTo = $request->get('today');
        $rep_id = $request->get('salesperson_id');
        //just for testing
        $dateTo = '2019-09-29';
        $rep_id = 35; //Matt Gutierrez
        $agent_name = SalesPerson::where('sales_person_id', $rep_id)->first();
//dd($agent_name->name);
        $saved_paid_commissions = $this->viewSavedPaidCommissions($rep_id, $paidCommissionDateFrom, $dateTo);
        $paid_subtotals_so = $this->paid_subtotals_so($rep_id, $paidCommissionDateFrom, $dateTo);
        $paid_subtotals_month = $this->paid_subtotals_month($rep_id, $paidCommissionDateFrom, $dateTo);
        $unpaid_subtotals_so = $this->unpaid_subtotals_so($rep_id, $paidCommissionDateFrom, $dateTo);
        $unpaid_subtotals_month = $this->unpaid_subtotals_month($rep_id, $paidCommissionDateFrom, $dateTo);
        //  dd( $unpaid_subtotals_month);
        $data = [];
        $commissions_paids = Salesline::select(DB::raw('*,
                EXTRACT(YEAR_MONTH FROM invoice_date) as summary_year_month
                '))
            ->orderBy('summary_year_month', 'desc')
            ->orderBy('order_number')
            ->where('sales_person_id', '=', $rep_id)
            ->where('state', 'like', 'paid')
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('commission_paid_at', '!=', NULL)
            ->get();

        $paids = [];
        foreach ($commissions_paids as $query) {
            $month = date("F", mktime(0, 0, 0, substr($query->summary_year_month, 4, 2), 1));

            array_push($paids, [
                'month' => $month . ' ' . substr($query->summary_year_month, 0, 4),
                'invoice_date' => $query->invoice_date,
                'order_number' => $query->order_number,
                'name' => $query->name,
                'quantity' => $query->quantity,
                'commission' => $query->commission,
                'unit_price' => $query->unit_price,
                'amount' => $query->amount,
            ]);
        }
        //  dd($commissions_paids);

        $commissions_unpaids = Salesline::select(DB::raw('*,
                EXTRACT(YEAR_MONTH FROM invoice_date) as summary_year_month
                '))
            ->orderBy('summary_year_month', 'desc')
            ->orderBy('order_number', 'desc')
            ->where('sales_person_id', '=', $rep_id)
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('state', 'like', 'paid')
            ->where('invoice_paid_at', '!=', NULL)
            ->where('commission_paid_at', '=', NULL)
            ->get();

//dd($commissions_unpaids->count());

        $unpaids = [];
        foreach ($commissions_unpaids as $query) {
            $month = date("F", mktime(0, 0, 0, substr($query->summary_year_month, 4, 2), 1));
            array_push($unpaids, [
                'month' => $month . ' ' . substr($query->summary_year_month, 0, 4),
                'invoice_date' => $query->invoice_date,
                'order_number' => $query->order_number,
                'name' => $query->name,
                'quantity' => $query->quantity,
                'commission' => $query->commission,
                'unit_price' => $query->unit_price,
                'amount' => $query->amount,
            ]);
        }
        return (view('paid_unpaid', ['name' => $agent_name->name, 'paid' => json_encode($paids), 'unpaid' => json_encode($unpaids)]));


        //  return view('paid_unpaid', compact('commissions_paids', 'commissions_unpaids'));
    }

    public function paid_subtotals_so($rep_id, $paidCommissionDateFrom, $dateTo)
    {
        $queries = SalesLine::select(DB::raw('*,
                        sum(commission) as sum_commission,
                        sum(amount) as sum_volume,
                        avg(NULLIF(margin,0))as avg_margin,
                        EXTRACT(YEAR_MONTH FROM saleslines.invoice_date) as summary_year_month
                        '))
            ->where('state', 'like', 'paid')
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('sales_person_id', '=', $rep_id)
            ->where('commission_paid_at', '!=', NULL)
            ->groupBy('order_number')
            ->get();
//dd($queries)->toArray();
        $paid_commissions_by_so = [];
        foreach ($queries as $query) {
            array_push($paid_commissions_by_so, [
                'customer_name' => $query->customer_name,
                'order_number' => $query->order_number,
                'commission_per_so' => $query->sum_commission,
                'volume_per_so' => $query->sum_volume,
                'margin_per_so' => $query->avg_margin,
            ]);
        }

        //      dd($paid_commissions_by_so);
        return ($paid_commissions_by_so);
    }

    public
    function paid_subtotals_month($rep_id, $paidCommissionDateFrom, $dateTo)
    {
        $queries = SalesLine::select(DB::raw('*,
                        sum(commission) as sum_commission,
                        sum(amount) as sum_volume,
                        avg(NULLIF(margin,0))as avg_margin,
                        EXTRACT(YEAR_MONTH FROM saleslines.invoice_date) as summary_year_month
                        '))
            ->where('state', 'like', 'paid')
            ->groupBy('summary_year_month')
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('sales_person_id', '=', $rep_id)
            ->where('commission_paid_at', '!=', NULL)
            ->get();
        $paid_commissions_by_month = [];
        foreach ($queries as $query) {
            array_push($paid_commissions_by_month, [
                'month' => $query->summary_year_month,
                'commission_per_month' => $query->sum_commission,
                'volume_per_month' => $query->sum_volume,
                'margin_per_month' => $query->avg_margin,
            ]);
        }
        //     dd($paid_commissions_by_month);
        return ($paid_commissions_by_month);
    }

    public
    function unpaid_subtotals_so($rep_id, $paidCommissionDateFrom, $dateTo)
    {
        $queries = SalesLine::select(DB::raw('*,
                        sum(commission) as sum_commission,
                        sum(amount) as sum_volume,
                        avg(NULLIF(margin,0))as avg_margin,
                        EXTRACT(YEAR_MONTH FROM saleslines.invoice_date) as summary_year_month
                        '))
            ->where('state', 'like', 'paid')
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('sales_person_id', '=', $rep_id)
            ->where('invoice_paid_at', '!=', NULL)
            ->where('commission_paid_at', '=', NULL)
            ->groupBy('order_number')
            ->get();

        $unpaid_commissions_by_so = [];
        foreach ($queries as $query) {
            array_push($unpaid_commissions_by_so, [
                'order_number' => $query->order_number,
                'commission_per_so' => $query->sum_commission,
                'volume_per_so' => $query->sum_volume,
                'margin_per_so' => $query->avg_margin,
            ]);
        }

        //    dd($unpaid_commissions_by_so);
        return ($unpaid_commissions_by_so);
    }

    public
    function unpaid_subtotals_month($rep_id, $paidCommissionDateFrom, $dateTo)
    {
        $queries = SalesLine::select(DB::raw('*,
                        sum(commission) as sum_commission,
                        sum(amount) as sum_volume,
                        avg(NULLIF(margin,0))as avg_margin,
                        EXTRACT(YEAR_MONTH FROM saleslines.invoice_date) as summary_year_month
                        '))
            ->where('state', 'like', 'paid')
            ->groupBy('summary_year_month')
            ->whereBetween('invoice_date', [$paidCommissionDateFrom, $dateTo])
            ->where('sales_person_id', '=', $rep_id)
            ->where('invoice_paid_at', '!=', NULL)
            ->where('commission_paid_at', '=', NULL)
            ->get();
        $unpaid_commissions_by_month = [];
        foreach ($queries as $query) {
            array_push($unpaid_commissions_by_month, [
                'month' => $query->summary_year_month,
                'commission_per_month' => $query->sum_commission,
                'volume_per_month' => $query->sum_volume,
                'margin_per_month' => $query->avg_margin,
            ]);
        }
        //     dd($unpaid_commissions_by_month);
        return ($unpaid_commissions_by_month);
    }

    public
    function calc_used_products()
    {

        $queries = InvoiceLine::select(DB::raw('*,
                count(product_id) as count_product,
                EXTRACT(YEAR_MONTH FROM invoice_date) as summary_year_month
                '))
            ->groupBy('product_id')
            ->orderBy('count_product', 'desc')
            ->get();
//dd($queries->toArray());
        foreach ($queries as $query) {

            echo $query->product_id . "      -> " . $query->name . "      : " . $query->count_product . "<br>";
        }
        /*        $products = Margin::all()
                ->rightJoin('')*/

    }
}

