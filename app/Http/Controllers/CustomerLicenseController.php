<?php

namespace App\Http\Controllers;

use App\Customer;
use App\BccAllLicense;
use Illuminate\Http\Request;

class CustomerLicenseController extends Controller
{
    public function index()
    {
        $no_licenses = Customer::where('license', '')->where('is_customer', true)->where('is_company', true)->get();
        foreach ($no_licenses as $no) {
            $bcc = BccAllLicense:: search($no->name)->get();
               dd($bcc);
            if ($bcc) {
                echo $no->name . " -> " . $bcc->businessName . "<br>";
            } else {
                echo $no->name . "<br>";
            }
        }
    }
}
