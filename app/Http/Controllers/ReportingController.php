<?php

namespace App\Http\Controllers;

use App\BccAllLicense;
use App\Exports\DreamersExport;
use App\SaleInvoice;
use App\Salesline;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use DataTables;

class ReportingController extends Controller
{
    public function index(Request $request)
    {
        $license_type = 'Cannabis - Retailer Nonstorefront License';
        $license_type=$request->get('bcc_type');
        $data = BccAllLicense::where('licenseType', $license_type)
            ->where('licenseStatus', 'Active')
            ->orderBy('id')
            ->get();
        //     dd($data->count());


        if (!$license_type = Session::get('bcc_type'))
            $license_type = 'Cannabis - Retailer Nonstorefront License';

        /*        $not_oz_datas = BccAllLicense::where('licenseType', $license_type)
                    ->where('ozCustomer', false)
                    ->where('licenseStatus', 'Active')
                    ->orderBy('id')
                    ->limit(10)
                    ->get();
                $bcc_data = [];
                    foreach ($not_oz_datas as $oz_data) {
                        $data = [
                            'customer' => $oz_data->businessName,
                            'license' => $oz_data->licenseNumber,
                            'city' => $oz_data->premiseCity,
                            'county' => $oz_data->premiseCounty,
                            'territory' => $oz_data->territory,
                            'at_oz' => $oz_data->ozCustomer ? "Yes" : "No",
                            'sales_person' => "",
                            'total_sales' => "",
                            'last_invoice' => "",
                        ];
                        array_push($bcc_data, $data);
                    }
                    dd($bcc_data);*/

        if ($request->ajax()) {
            $oz_datas = BccAllLicense::where('licenseType', $license_type)
                ->where('ozCustomer', true)
                ->with('customer')
                ->with('invoices')
                ->with('last_invoice')
                ->has('invoices')
                ->has('last_invoice')
                ->withSum(['invoices:amount_untaxed' => function (Builder $query) {
                    $query->where('type', 'like', 'out_invoice');
                }])
                ->where('licenseStatus', 'Active')
                ->orderBy('id')
                ->get();

            $bcc_data = [];

            foreach ($oz_datas as $oz_data) {
                $data = [
                    'customer' => $oz_data->customer->name,
                    'license' => $oz_data->customer->license,
                    'city' => $oz_data->customer->city,
                    'county' => $oz_data->premiseCounty,
                    'territory' => $oz_data->territory,
                    'at_oz' => $oz_data->ozCustomer ? "Yes" : "No",
                    'sales_person' => $oz_data->customer->sales_person,
                    'total_sales' => number_format($oz_data->invoices_amount_untaxed_sum, 2),
                    'last_invoice' => $oz_data->last_invoice->invoice_date,
                ];
                array_push($bcc_data, $data);
            }

            $not_oz_datas = BccAllLicense::where('licenseType', $license_type)
                ->where('ozCustomer', false)
                ->where('licenseStatus', 'Active')
                ->orderBy('id')
                ->get();

            foreach ($not_oz_datas as $oz_data) {
                $data = [
                    'customer' => $oz_data->businessName,
                    'license' => $oz_data->licenseNumber,
                    'city' => $oz_data->premiseCity,
                    'county' => $oz_data->premiseCounty,
                    'territory' => $oz_data->territory,
                    'at_oz' => $oz_data->ozCustomer ? "Yes" : "No",
                    'sales_person' => "",
                    'total_sales' => "",
                    'last_invoice' => "",
                ];
                array_push($bcc_data, $data);
            }

            $bcc_data = collect($bcc_data);
            return Datatables::of($bcc_data)
/*                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action']) */
                ->make(true);
        } else {
            $bcc_type = $request->get('bcc_type');
            Session::put('bcc_type', $bcc_type);
        }
        return view('bcc.bcc_extended_2');
    }


    public function dreamers_report()
    {
        $sales_lines = \DB::table('saleinvoices')->selectRaw("*,
        sum(price_subtotal) as customer_total_amount,
        sum(quantity) as customer_total_quantity,
        customers.name as customer_name,
        customers.email as customer_email
        ")
            ->leftJoin('customers', 'customers.ext_id', '=', 'saleinvoices.customer_id')
            ->where('cat_sub3', 'like', '%' . 'dreamers' . '%')
            ->where('price_subtotal', '>', 1)
            ->groupBy('customer_id')
            ->get();
        return Excel::download(new DreamersExport($sales_lines), 'dreamers.xlsx');
    }
}
