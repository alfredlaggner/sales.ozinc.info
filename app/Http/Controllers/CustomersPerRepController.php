<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Invoice;
use \App\Customer;
use \App\User;
use Illuminate\Support\Facades\DB;
use DataTables;
use Session;

class CustomersPerRepController extends Controller
{
    public function index(Request $request, $rep_id = 0)
    {
    //    dd('xxx');
        if (!$rep_id) {
            $rep_id = $request->get('salesperson_id');
        }

        $rep_id = Session::get('rep_id');

        $customers = Customer::select(DB::raw('
                        customers.ext_id,
                        customers.sales_person as rep_name,
                        customers.user_id as rep,
                        customers.name as name,
                        customers.total_overdue as due,
                        invoices.invoice_date as invoice_date,
                        sum(invoices.amount_untaxed) as amount,
                        count(invoices.sales_order) as invoices,
                        YEAR(invoices.invoice_date) year, MONTH(invoices.invoice_date) month,
                        aged_receivables_totals.is_felon as grade
                        '))
            ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.ext_id')
            ->leftJoin('aged_receivables_totals', 'aged_receivables_totals.customer_id', '=', 'customers.ext_id')
            ->where('invoices.type', '=', 'out_invoice')
            ->where('customers.user_id', '=', $rep_id)
            ->where('customers.name', 'not like', '%sample%')
            ->where('customers.name', 'not like', '%zz%')
            ->groupBy('customers.ext_id')
            ->orderBy('amount', 'desc')
            ->get();

        $chart_array = [];
        foreach ($customers as $customer) {
            array_push($chart_array, [
                'name' => $customer->name,
                'amount' => $customer->amount,
            ]);
        }
        // dd($chart_array);
        $chart_array = collect($chart_array);
        Session::put('rep_id', $rep_id);
        $rep_name = User::where('sales_person_id', $rep_id)->pluck('name');
        return view('sales.customer_invoices_list', compact('rep_name', 'chart_array'));
    }

    public function ajax_index(Request $request, $rep_id = 0)
    {
        if (!$rep_id) {
            $rep_id = $request->get('salesperson_id');
        }

        $rep_id = Session::get('rep_id');

        $customers = Customer::select(DB::raw('
                        customers.ext_id,
                        customers.sales_person as rep_name,
                        customers.user_id as rep,
                        customers.name as name,
                        customers.total_overdue as due,
                        invoices.invoice_date as invoice_date,
                        sum(invoices.amount_untaxed) as amount,
                        count(invoices.sales_order) as invoices,
                        YEAR(invoices.invoice_date) year, MONTH(invoices.invoice_date) month,
                        aged_receivables_totals.is_felon as grade
                        '))
            ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.ext_id')
            ->leftJoin('aged_receivables_totals', 'aged_receivables_totals.customer_id', '=', 'customers.ext_id')
            ->where('invoices.type', '=', 'out_invoice')
            ->where('customers.user_id', '=', $rep_id)
            ->where('customers.name', 'not like', '%sample%')
            ->where('customers.name', 'not like', '%zz%')
            ->groupBy('customers.ext_id')
            ->orderBy('amount', 'desc')
            ->get();


        $customer_array = [];
        foreach ($customers as $customer) {
            if ($customer->payment) {
                $payment_date = $customer->payment->payment_date;
            } else {
                $payment_date = '';
            }
            array_push($customer_array, [
                'grade' => $customer->grade,
                'name' => $customer->name,
                'invoices' => number_format($customer->invoices, 0),
                'due' => number_format($customer->due, 2),
                'amount' => $customer->amount,
                'invoice_date' => $customer->invoice->invoice_date,
                'payment_date' => $payment_date,
                'customer_id' => $customer->ext_id,
                'rep' => $customer->rep,
            ]);
        }

        return Datatables::of($customer_array)
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function ajax_invoices($customer_id)
    {
        $invoices = Invoice::where('customer_id', $customer_id)
            ->orderby('invoice_date','desc')
        ->get();
  //      return view('/sales.ajax_invoices',compact('invoices'));
        $returnHTML = view('/sales.ajax_invoices',compact('invoices'))->render();
        return $returnHTML;
    }

    public function ajax_totalindex(Request $request, $rep_id = 0)
    {
        if (!$rep_id) {
            $rep_id = $request->get('salesperson_id');
        }

        if ($request->ajax()) {

            $rep_id = Session::get('rep_id');

            $customers = Customer::select(DB::raw('
                        customers.ext_id,
                        customers.sales_person as rep_name,
                        customers.user_id as rep,
                        customers.name as name,
                        customers.total_overdue as due,
                        invoices.invoice_date as invoice_date,
                        sum(invoices.amount_untaxed) as amount,
                        count(invoices.sales_order) as invoices,
                        YEAR(invoices.invoice_date) year, MONTH(invoices.invoice_date) month,
                        aged_receivables_totals.is_felon as grade
                        '))
                ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.ext_id')
                ->leftJoin('aged_receivables_totals', 'aged_receivables_totals.customer_id', '=', 'customers.ext_id')
                ->where('invoices.type', '=', 'out_invoice')
                ->where('customers.user_id', '=', $rep_id)
                ->where('customers.name', 'not like', '%sample%')
                ->having('due', '>', 100)
                ->groupBy('customers.ext_id')
                ->orderBy('amount', 'desc')
                ->get();

            $customer_array = [];
            foreach ($customers as $customer) {
                if ($customer->payment) {
                    $payment_date = $customer->payment->payment_date;
                } else {
                    $payment_date = '';
                }
                array_push($customer_array, [
                    'grade' => $customer->grade,
                    'name' => $customer->name,
                    'invoices' => number_format($customer->invoices, 0),
                    'due' => number_format($customer->due, 2),
                    'amount' => number_format($customer->amount, 2),
                    'invoice_date' => $customer->invoice->invoice_date,
                    'payment_date' => $payment_date,
                    'customer_id' => $customer->ext_id,
                ]);
            }


            //  dd($customers->toArray());

            return Datatables::of($customer_array)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        Session::put('rep_id', $rep_id);
        $rep_name = User::where('sales_person_id', $rep_id)->pluck('name');
        return view('ar.customers_table_total_datatable', compact('rep_name', 'rep_id'));

    }

}
