<?php


namespace App\Exports;

use App\AgedReceivable;
use App\TenNinetyCommission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class TenNinetyExport implements FromView
{
    public function __construct($data)
    {
        $this->data = $data;
       // dd($this->data);
    }

    public function view(): View
    {
        $ars = TenNinetyCommission::where('month', $this->data[0])
            ->where('year',$this->data[2])
            ->where('half',$this->data[1])
            ->where('goal', '>', 0)
            ->where('volume', '>', 0)
       //     ->orderby('rep_name','asc')
            ->get();
    //    dd($ars);
        return view('exports.ten_ninety_commissions', ['ars' => $ars]);
    }
}
