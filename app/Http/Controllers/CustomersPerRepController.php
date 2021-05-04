<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Invoice;
use \App\Customer;
use \App\User;
use Illuminate\Support\Facades\DB;

class CustomersPerRepController extends Controller
{
    public function xindex(Request $request, $rep_id = 0)
    {
        if (!$rep_id) {
            $rep_id = $request->get('salesperson_id');
        }

        if ($rep_id) {
            $invoices = Invoice::select(DB::raw('
                customer_id,
                customer_name,
				sum(amount_untaxed) as amount,
				sum(residual) as due,
				count(sales_order) as invoices,
				YEAR(invoice_date) year, MONTH(invoice_date) month
                '))
                ->where('type', '=', 'out_invoice')
                ->where('sales_person_id', '=', $rep_id)
                ->groupBy('customer_id')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->orderBy('amount', 'desc')
                ->get();
            //  dd($invoices->toArray());

//            $invoices =$invoices->sortBy('year')->sortBy('customer_name');
        }
        return view('customers_table', compact('invoices'));
    }

    public function odindex(Request $request, $rep_id = 0)
    {
        if (!$rep_id) {
            $rep_id = $request->get('rep_id');
        }
        if ($rep_id) {
            $invoices = Customer::select(DB::raw('
                invoices.customer_id,
                invoices.customer_name,
				sum(invoices.amount_untaxed) as amount,
				sum(invoices.residual) as due,
				count(invoices.sales_order) as invoices,
				YEAR(invoice_date) year, MONTH(invoice_date) month
                '))
                ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.ext_id')
                ->where('invoices.type', '=', 'out_invoice')
                ->where('customers.user_id', '=', $rep_id)
                ->groupBy('customers.ext_id')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->orderBy('amount', 'desc')
                ->get();
            //  dd($invoices->toArray());

//            $invoices =$invoices->sortBy('year')->sortBy('customer_name');
        }
        return view('customers_table', compact('invoices'));
    }

    public function totalindex(Request $request, $rep_id = 0)
    {
     //   dd($request);
        if (!$rep_id) {
            $rep_id = $request->get('salesperson_id');
        }
        if ($rep_id) {


            $customers = Customer::select(DB::raw('
                customers.ext_id,
                customers.sales_person as rep_name,
                customers.user_id as rep,
                customers.name as name,
                customers.total_overdue as due,
                sum(invoices.amount_untaxed) as amount,
				count(invoices.sales_order) as invoices,
				YEAR(invoices.invoice_date) year, MONTH(invoices.invoice_date) month,
				aged_receivables_totals.is_felon as grade
                '))
                ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.ext_id')
                ->leftJoin('aged_receivables_totals', 'aged_receivables_totals.customer_id', '=', 'customers.ext_id')
                ->where('invoices.type', '=', 'out_invoice')
                ->where('customers.user_id', '=', $rep_id)
                ->where('customers.name','not like', '%sample%')
                ->having('due', '>', 100)

                //    ->groupBy('invoices.customer_id')
                ->groupBy('customers.ext_id')
                //    ->orderBy('year','desc')
        //        ->orderBy('customers.name', 'asc')
                ->orderBy('amount', 'desc')
                ->get();
            //  dd($customers->toArray());
            $user = User::where('sales_person_id',$rep_id)->first();
            $rep_name = $user->name;
        }

        return view('ar.customers_table_total', compact('customers','rep_name'));
    }

    public function index(Request $request, $rep_id = 71)
    {
        if (!$rep_id) {
            $rep_id = $request->get('rep_id');
        }
        if ($rep_id) {
            $customers = Customer::select(DB::raw('
                customers.ext_id,
                customers.user_id as rep,
                customers.name as name,
                customers.total_overdue as due,
                sum(invoices.amount_untaxed) as amount,
				count(invoices.sales_order) as invoices,
				YEAR(invoices.invoice_date) year, MONTH(invoices.invoice_date) month
                '))
                ->leftJoin('invoices', 'invoices.customer_id', '=', 'customers.ext_id')
                ->where('invoices.type', '=', 'out_invoice')
                ->where('customers.user_id', '=', $rep_id)
                //    ->groupBy('invoices.customer_id')
                ->groupBy('customers.ext_id')
                //    ->orderBy('year','desc')
                ->orderBy('customers.name', 'asc')
                ->orderBy('amount', 'desc')
                ->get();
            //  dd($customers->toArray());
            $user = User::find('rep_id')->first;
            $rep_name = $user->name;
//            $invoices =$invoices->sortBy('year')->sortBy('customer_name');
        }
        return view('customers_table', compact('invoices', 'rep_name'));
    }
}
