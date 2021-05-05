<?php
/**
 *
 * @mixin Eloquent
 */

namespace App\Http\Controllers;

use App\Payment;
use PDF;
use Gate;
use Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\AgedReceivable;
use App\AgedReceivablesTotal;
use App\Exports\AgedReceivableDetailExport;
use App\Exports\AgedReceivableTotalExport;
use App\Exports\InvoiceNoteExport;
use App\Invoice;
use App\InvoiceLine;
use App\InvoiceAmountCollect;
use App\InvoiceNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redis;

class ArTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function xyz()
    {
        dd("xyz");
    }

    public function new_aged_receivables(Request $request, $rep_id = 0, $customer_id = 0)
    {
        //   dd($request);
        /*        Artisan::call('tntsearch:import', ['model' => 'App\AgedReceivable']);
                Artisan::call('tntsearch:import', ['model' => 'App\AgedReceivablesTotal']);*/

        $data = [];
        if (!$rep_id) {
            $rep_id = $request->get('salesperson_id');
        }
        if (!$customer_id) {
            $customer_id = $request->get('customer_id');
        }
//dd($customer_id);
        $customer = $request->get('customer');

        session(['rep_id' => $rep_id]);

        if ($rep_id) {

            $ars = AgedReceivable::
            when($rep_id, function ($query, $rep_id) {
                return $query->where('rep_id', $rep_id);
            })
                ->minbalancedetail()
                ->orderBy('customer')
                ->get();
            if ($customer) {
                if ($value = Redis::get('ars_totals1.all')) {
                    $ars_totals = collect(json_decode($value));
                } else {
                    $ars_totals = AgedReceivablesTotal::search($customer)->where('rep_id', $rep_id)->get();
                    //       Redis::set('ars_totals1.all', $ars_totals);
                }
                session(['search_value' => '$customer']);
            } else {
                if ($value = Redis::get('ars_totals2.all')) {
                    $ars_totals = collect(json_decode($value));

                } else {
                    $ars_totals = AgedReceivablesTotal::
                    when($rep_id, function ($query, $rep_id) {
                        return $query->where('rep_id', $rep_id);
                    })->minbalance()->orderBy('customer')->get();

                    //          Redis::set('ars_totals2.all', $ars_totals);
                }

                session(['search_value' => $rep_id]);

            }
        } elseif ($customer_id) {
            $ars = AgedReceivable::
            when($customer_id, function ($query, $customer_id) {
                return $query->where('customer_id', $customer_id);
            })->minbalancedetail()->orderBy('customer')
                ->get();

            $ars_totals = AgedReceivablesTotal::
            when($customer_id, function ($query, $customer_id) {
                return $query->where('customer_id', $customer_id);
            })->minbalance()->orderBy('customer')
                ->get();

        } else {
            if ($customer) {
                if ($value = Redis::get('ars_totals3.all')) {
                    $ars_totals = collect(json_decode($value));
                } else {
                    $ars_totals = AgedReceivablesTotal::search($customer)->minbalance()->get();
                    session(['search_value' => $customer]);
                    //            Redis::set('ars_totals3.all', $ars_totals);
                }
            } else {
                if ($value = Redis::get('ars_totals4.all')) {
                    $ars_totals = collect(json_decode($value));
                } else {

                    $ars_totals = AgedReceivablesTotal::orderBy('customer')->minbalance()->get();
                    //           Redis::set('ars_totals4.all', $ars_totals);
                    session(['search_value' => '']);
                }
            }
            if ($value = Redis::get('ars.all')) {
                $ars = collect(json_decode($value));

            } else {
                $ars = AgedReceivable::minbalancedetail()->orderBy('customer')->get();
                //       Redis::set('ars.all', $ars);
            }
        }

        $amt_collects = InvoiceAmountCollect::orderBy('updated_at', 'desc')->get();
        $notes = InvoiceNote::orderBy('updated_at', 'desc')->get();

//		$request->session()->forget('previous_screen'); // to start clean when adding notes
        return (view('ar.accordion', compact('ars', 'ars_totals', 'notes', 'amt_collects', 'rep_id')));
    }

    public function ajax_sales_order($sales_order_id = 0)
    {

        $invoice = Invoice::where('sales_order', $sales_order_id)->first();
        $invoice_lines = InvoiceLine::where('invoice_number', $sales_order_id)->get();

        return (view('ar.ajax_sales_order', compact('invoice', 'invoice_lines')));
    }

    public function ajax_new_aged_receivables($customer_id = 0)
    {
        $ars = AgedReceivable::
        when($customer_id, function ($query, $customer_id) {
            return $query->where('customer_id', $customer_id);
        })->minbalancedetail()->orderBy('customer')
            ->get();
        $ars_totals = AgedReceivablesTotal::minbalance()
            ->when($customer_id, function ($query, $customer_id) {
                return $query->where('customer_id', $customer_id);
            })->orderBy('customer')
            ->where('residual', '>', 100)
            ->get();

        $amt_collects = InvoiceAmountCollect::orderBy('updated_at', 'desc')->get();
        $notes = InvoiceNote::orderBy('updated_at', 'desc')->get();

        return (view('ar.ajax_accordion', compact('ars', 'ars_totals', 'notes', 'amt_collects')));
    }
    public function ajax_new_aged_receivables_2($customer_id = 0)
    {
        $ars = AgedReceivable::
        when($customer_id, function ($query, $customer_id) {
            return $query->where('customer_id', $customer_id);
        })->minbalancedetail()->orderBy('customer')
            ->get();
        $ars_totals = AgedReceivablesTotal::minbalance()
            ->when($customer_id, function ($query, $customer_id) {
                return $query->where('customer_id', $customer_id);
            })->orderBy('customer')
            ->where('residual', '>', 100)
            ->get();

        $amt_collects = InvoiceAmountCollect::orderBy('updated_at', 'desc')->get();
        $notes = InvoiceNote::orderBy('updated_at', 'desc')->get();

        $returnHTML = view('ar.ajax_accordion', compact('ars', 'ars_totals', 'notes', 'amt_collects'))->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    //    return (view('ar.ajax_accordion', compact('ars', 'ars_totals', 'notes', 'amt_collects')));
    }
}
