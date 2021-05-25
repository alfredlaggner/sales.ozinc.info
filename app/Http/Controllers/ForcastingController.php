<?php

namespace App\Http\Controllers;

use App\SalesOrder;
use App\StockPicking;
use App\Customer;
use App\User;
use App\Invoice;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class ForcastingController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('start');
        $to = $request->get('end');
/*        $from = $from . " 00:00:00";
        $to = $to . " 00:00:00";*/
      //  dd($to);
        $rep_id = $request->get('salesperson_id');
        if (!$rep_id) $rep_name = "All";
        else $rep_name = User::where('sales_person_id', $rep_id)->value('name');
        // dd($rep_name);
        session(['from' => $from, 'to' => $to, 'rep_id' => $rep_id]);

        $forcasts = $this->get_forcast();
        $sum_14 = $sum_cod = $sum_other = 0;
        $totals = [];

        foreach ($forcasts as $forcast) {
            if ($forcast['term_id'] == 1)
                $sum_14 += $forcast['amount'];
            if ($forcast['term_id'] == 10)
                $sum_cod += $forcast['amount'];
        }

        array_push($totals, ['sum_term' => $sum_14, 'sum_cod' => $sum_cod, 'sum_other' => $sum_other]);
        return view('sales.forcasts', compact('totals', 'from', 'to', 'rep_name'));
    }

    public function ajax_index()
    {
        $forcast_array = collect($this->get_forcast());
        return Datatables::of($forcast_array)
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_forcast()
    {
        $from = session('from');
        $to = session('to');
        $rep_id = session('rep_id');
       //  $rep_id =71;
        $is_rep = $rep_id;

        /*        $from = '2021-05-01';
                $to = '2021-05-25';
                $from = $request->get('from');
                $to = $request->get('to');*/
        $all_terms = [];

        $terms = Invoice::selectRaw("*, sum(amount_untaxed) as sum_amount_untaxed")
            ->with('customer')
            ->with('salesorder')
            ->whereHas('salesorder', function($q) use($from, $to){
                $q->where('confirmation_date', ">=", $from);
                $q->where('confirmation_date', "<=", $to);
            })
            ->where('state','!=','paid')
            ->whereIn('payment_term_id', [1, 2, 3, 5, 6, 7, 10])
            ->when($is_rep, function ($query) use ($rep_id) {
                return $query->where('sales_person_id', $rep_id);
            })
            ->groupBy('customer_id')
            ->orderByRelation('customer:term_id')
            ->get();

      //  dd($terms);
        $all_terms = [];
        foreach ($terms as $t) {
            array_push($all_terms, [
                'name' => $t->customer->name,
                'due_date' => $t->due_date,
                'confirmation_date' => $t->salesorder->confirmation_date,
                'term' => $t->customer->term_name,
                'term_id' => $t->customer->term_id,
                'amount' => $t->sum_amount_untaxed,
                'customer_id' => $t->customer_id,
            ]);
        }
        return $all_terms;
    }


    public function ajax_salesorders($customer_id)
    {
        $from = session('from');
        $to = session('to');
        $rep_id = session('rep_id');
        $is_rep = $rep_id;
        $sps = Invoice::with('customer')
            ->where('customer_id', $customer_id)
            ->where('state','!=','paid')
            ->whereIn('payment_term_id', [1, 2, 3, 5, 6, 7, 10])
            ->with('salesorder')
            ->whereHas('salesorder', function($q) use($from, $to){
                $q->where('confirmation_date', ">=", $from);
                $q->where('confirmation_date', "<=", $to);
            })

          //  ->whereBetween('due_date', [$from, $to])
            ->when($is_rep, function ($query, $rep_id) use ($is_rep) {
                return $query->where('sales_person_id', $rep_id);
            })
            ->get();
        $forcast_array = [];
        foreach ($sps as $sp) {
            array_push($forcast_array, [
                'name' => $sp->sales_order,
                'confirmation_date' => $sp->salesorder->confirmation_date,
                'due_date' => $sp->due_date,
                'amount' => $sp->amount_untaxed
            ]);
        }

        //  dd($forcast_array);

        return view('sales.ajax_forcast_salesorders', compact('forcast_array'))->render();

    }


}
