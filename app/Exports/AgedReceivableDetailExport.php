<?php

namespace App\Exports;

use App\AgedReceivable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class AgedReceivableDetailExport implements FromView
{
    public function __construct($data)
    {
        $this->data = $data;
    }
        public function view(): View
        {
            if ($this->data) {
                $ars = AgedReceivable::where('rep_id', $this->data)->minbalancedetail()->orderby('rep')->orderby('customer')->get();
            } else {
                $ars = AgedReceivable::orderby('rep')->minbalancedetail()->orderby('customer')->get();
            }

            return view('exports.ar_details', ['ars' => $ars]);
        }
    }
