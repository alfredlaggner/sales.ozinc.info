<?php

namespace App\Http\Controllers;

use App\StockPicking;
use App\Customer;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class ForcastingController extends Controller
{
    public function index(Request $request)
    {

        $forcasts = collect($this->get_forcast($request));
        //  dd($forcasts->toarray());

        $sum_14 = $sum_cod = $sum_other = 0;
        $totals = [];
        foreach ($forcasts[0] as $forcast) {
            $sum_14 += $forcast['sum_term'];
        }
        foreach ($forcasts[1] as $forcast) {
            $sum_cod += $forcast['sum_term'];
        }

        array_push($totals, ['sum_term' => $sum_14, 'sum_cod' => $sum_cod, 'sum_other' => $sum_other]);
        return view('sales.forcasts', compact('totals'));
    }

    public function ajax_index()
    {

        $forcasts = collect($this->get_forcast());
        $forcast_array = [];

        foreach ($forcasts[0] as $forcast) {
            array_push($forcast_array, [
                'name' => $forcast['customer']['name'],
                'term' => $forcast['customer']['term_id'],
                'amount' => $forcast['sum_term'],
                //  'sales_order' => $forcast['salesorder']['sales_order'],
            ]);
        }
        foreach ($forcasts[1] as $forcast) {
            array_push($forcast_array, [
                'name' => $forcast['customer']['name'],
                'term' => $forcast['customer']['term_id'],
                'amount' => $forcast['sum_term'],
                //  'sales_order' => $forcast['salesorder']['sales_order'],
            ]);
        }
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

        /*        $from = $request->get('from');
                $to = $request->get('to');*/

        $from = '2021-04-01';
        $to = '2021-05-13';
        $all_terms = [];
        $term_14 = StockPicking::select('*')
            ->with('customer')->whereHas('customer',
                function ($q) {
                    $q->where('term_id', 1);
                })
            ->with('salesorder')->whereHas('salesorder')
            ->withSum('salesorder:amount_untaxed as sum_term')
            ->whereYear('date', '2021')
            ->whereBetween('date', [$from, $to])
            ->where('state', 'done')
            ->groupBy('partner_id')
            ->orderBy('partner_id')
            ->get()->toArray();
//dd($term_14);
        array_push($all_terms, $term_14);
        //   dd($all_terms);

        $term_cod = StockPicking::select('*')
            ->with('customer')->whereHas('customer',
                function ($q) {
                    $q->where('term_id', 10);
                })
            ->with('salesorder')->whereHas('salesorder')
            ->withSum('salesorder:amount_untaxed as sum_term')
            ->whereYear('date', '2021')
            ->whereBetween('date', [$from, $to])
            ->where('state', 'done')
            ->groupBy('partner_id')
            ->orderBy('partner_id')
            ->get()->toArray();
        // dd($term_cod);
        array_push($all_terms, $term_cod);
        return $all_terms;
    }


}
