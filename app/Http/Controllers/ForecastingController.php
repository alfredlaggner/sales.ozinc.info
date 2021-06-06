<?php

namespace App\Http\Controllers;

use App\SalesOrder;
use App\StockPicking;
use App\Customer;
use App\TermSum;
use App\User;
use App\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class ForecastingController extends Controller
{
    public function index(Request $request)
    {
        $start = date('Y-m-d', strtotime("- 1 month"));
        $end = date('Y-m-d', strtotime("+ 14 days"));

        if (session('is_rep')) {

            $validated = $request->validate([
                'start' => 'date_format:Y-m-d|after_or_equal:' . $start,
                'end' => 'date_format:Y-m-d|before_or_equal:' . $end]);
        } else {
            $validated = $request->validate([
                'start' => 'date_format:Y-m-d' ,
                'end' => 'date_format:Y-m-d']);
        }

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

        $forecasts = $this->get_forecast();
        //   dd($forecasts);
        $totals = $forecasts[1];
        $total = $forecasts[2];
        //  dd($total);
        return view('sales.forecasts', compact('totals', 'total', 'from', 'to', 'rep_name'));
    }

    public function ajax_index()
    {
        $forecast_array = collect($this->get_forecast());
        //     dd($forecast_array);
        return Datatables::of($forecast_array[0])
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_forecast()
    {
        $from = session('from');
        $to = session('to');
        $rep_id = session('rep_id');
        $is_rep = $rep_id;
        $all_terms = [];

        $terms = SalesOrder::selectRaw("*, sum(amount_untaxed) as sum_amount_untaxed")
            ->with('customer')
                      ->whereHas('customer',
                  function ($q) {
                              $q->whereIn('term_id', [1, 2, 3, 5, 6, 7, 10]);
                          })
            ->whereBetween('confirmation_date', [$from, $to])
            ->where('amount_untaxed','>', 1)
            ->when($is_rep, function ($query) use ($rep_id) {
                return $query->where('salesperson_id', $rep_id);
            })
            ->groupBy('customer_id')
            ->orderByRelation('customer:term_id')
            ->get();
/*        echo $from . "<br>";
        echo $to. "<br>";
        dd($terms);*/
        //     dd($terms);
        foreach ($terms as $t) {
            array_push($all_terms, [
                'name' => $t->customer->name,
                'expected_date' => $t->expected_date,
                'confirmation_date' => $t->confirmation_date,
                'term' => $t->customer->term_name,
                'term_id' => $t->customer->term_id,
                'amount' => $t->sum_amount_untaxed,
                'customer_id' => $t->customer_id,
            ]);
        }
        $term_sums = SalesOrder::selectRaw("customers.term_name as customers_term_name, sum(amount_untaxed) as sum_term")
            ->whereBetween('confirmation_date', [$from, $to])
            ->leftJoin('customers', 'customers.ext_id', '=', 'salesorders.customer_id')
            ->whereIn('customers.term_id', [1, 2, 3, 5, 6, 7, 10])
            ->where('amount_untaxed','>', 1)
            ->when($is_rep, function ($query) use ($rep_id) {
                return $query->where('salesperson_id', $rep_id);
            })
            ->groupBy('customers.term_id')
            ->get()->toArray();

        $total_sum = SalesOrder::selectRaw("sum(amount_untaxed) as total_sum")
            ->whereBetween('confirmation_date', [$from, $to])
            ->leftJoin('customers', 'customers.ext_id', '=', 'salesorders.customer_id')
            ->whereIn('customers.term_id', [1, 2, 3, 5, 6, 7, 10])
            ->where('amount_untaxed','>', 1)
            ->when($is_rep, function ($query) use ($rep_id) {
                return $query->where('salesperson_id', $rep_id);
            })
            ->get()->toArray();

        //   array_push($all_terms,[$term_sums]);
        // dd($total_sum);
        return [$all_terms, $term_sums, $total_sum];
    }


    public function ajax_salesorders($customer_id)
    {
        $from = session('from');
        $to = session('to');

        $rep_id = session('rep_id');
        $is_rep = $rep_id;
        $sps = SalesOrder::with('customer')
            ->with('invoice')
            ->where('customer_id', $customer_id)
            ->whereHas('customer',
                function ($q) {
                    $q->whereIn('term_id', [1, 2, 3, 5, 6, 7, 10]);
                })
            ->where('amount_untaxed','>', 1)
            ->whereBetween('confirmation_date', [$from, $to])
            ->when($is_rep, function ($query, $rep_id) use ($is_rep) {
                return $query->where('salesperson_id', $rep_id);
            })
            ->get();
        $forecast_array = [];

        foreach ($sps as $sp) {
            $confirmation_date = substr($sp->confirmation_date, 0, 10);
            //  $confirmation_date = $sp->invoice->invoice_date;
            $due_date = $confirmation_date;

            switch ($sp->customer->term_id) {
                case 6:
                case 1:
                    $due_date = date('Y-m-d', strtotime($confirmation_date . '+ 16 days'));
                    break;
                case 2:
                    $due_date = date('Y-m-d', strtotime($confirmation_date . '+ 15 days'));
                    break;
                case 3:
                    $due_date = date('Y-m-d', strtotime($confirmation_date . '+ 33 days'));
                    break;
                case 5:
                    $due_date = date('Y-m-d', strtotime($confirmation_date . '+ 7 days'));
                    break;
                case 7:
                    $due_date = date('Y-m-d', strtotime($confirmation_date . '+ 21 days'));
                    break;
                case 10:
                    $due_date = date('Y-m-d', strtotime($confirmation_date . '+ 0 days'));
            }
            array_push($forecast_array, [
                'name' => $sp->sales_order,
                'confirmation_date' => $sp->confirmation_date,
                'due_date' => $due_date,
                'amount' => $sp->amount_untaxed
            ]);
        }

        //  dd($forecast_array);

        return view('sales.ajax_forecast_salesorders', compact('forecast_array'))->render();

    }


}
