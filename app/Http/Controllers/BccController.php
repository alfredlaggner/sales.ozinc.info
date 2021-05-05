<?php

namespace App\Http\Controllers;

use App\Customer;
use App\BccAllLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
//use Yajra\Datatables\Facades\Datatables;
use DataTables;

class BccController extends Controller
{
    public function index(Request $request)
    {

        if (!$license_type = Session::get('bcc_type'))
            $license_type = 'Cannabis - Retailer License';

        if ($request->ajax()) {
            $data = BccAllLicense::where('licenseType', $license_type)
                ->where('licenseStatus', 'Active')
                ->orderBy('id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            $bcc_type = $request->get('bcc_type');
            Session::put('bcc_type', $bcc_type);
        }

        return view('bcc.bcc_main', compact ('bcc_type'));

    }

    public function bcc_distributers(Request $request)
    {
        if ($request->ajax()) {
            $data = BccAllLicense::where('licenseType', 'Cannabis - Distributor License')
                ->where('licenseStatus', 'Active')
                ->orderBy('id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('bcc.bcc_main');
    }

    public function xindex()
    {

        $customers = Customer::limit(100)->with('bcc')->whereHas('bcc')->get();
        foreach ($customers as $customer) {
            echo $customer->bcc->premiseCity . '<br>';
        }

        return false;
    }

    public function xbcc()
    {

        $bccs = BccAllLicense::with('ozRegion')
            ->whereHas('ozRegion')
            ->whereHas('ozCustomer')
            //     ->whereNotNull('premiseZip')
            ->where('licenseType', 'Cannabis - Retailer License')
            ->where('licenseStatus', 'Active')
            ->orderBy('id')
            ->get();
        //  dd($bccs);
        /*        foreach ($bccs as $bcc) {
                    echo $bcc->id . ': ' . $bcc->ozRegion->id. '<br>';
                }
        dd('xxx');*/
        return view('bcc.bcc', compact('bccs'));
    }

    public function bcc()
    {

        $bccs = BccAllLicense::
        where('licenseType', 'Cannabis - Retailer License')
            ->where('licenseStatus', 'Active')
            ->orderBy('id')
            ->get();
        return view('bcc.bcc', compact('bccs'));

        //  dd($bccs);
        /*        foreach ($bccs as $bcc) {
                    echo $bcc->id . ': ' . $bcc->ozRegion->id. '<br>';
                }
        dd('xxx');*/
        /*        return Laratables::recordsOf(BccAllLicense::class/*, function($query)
                {
                    return $query->where('licenseType', 'Cannabis - Retailer License')
                            ->where('licenseStatus', 'Active')
                            ->orderBy('id');
                }*/
    }
}
