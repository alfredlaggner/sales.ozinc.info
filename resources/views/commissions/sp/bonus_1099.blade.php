<!--<h6> Bonus Commissions</h6>
--><div class="card-body">
    @php
        $i = 1;
    @endphp

    <div class="accordion" id="bonus_per_month">
        @for ($i=0; $i < count($totals); $i++)
            @php

                $disp_year = $totals[$i]['bonus_period']->year;
                $disp_month = date("F", mktime(0, 0, 0, $totals[$i]['bonus_period']->month, 10));
                $half = $totals[$i]['bonus_period']->half;
                $locale = 'en_US';
                $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
                $disp_half = $nf->format($half);
                $total_amount = $totals[$i]['totals'][0]->sp_amount;
                $total_commission = $totals[$i]['totals'][0]->sp_commission;
            @endphp
            <div class="card">
                <div class="card-header" id="heading_{{$i}}">
                    <h2 class="mb-0 d-inline">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapse_{{$i}}" aria-expanded="false"
                                aria-controls="collapse_{{$i}}">
                            <table id="accounts" class="table table-bordered table-hover table-sm table-responsive-lg"
                                   style=" table-layout: fixed ; width: 100% " ;>
                                <thead>
                                <tr>
                                    <th style="width:33%" class="text-xl-left"></th>
                                    <th class="text-xl-right">Total Untaxed</th>
                                    <th class="text-xl-right">Total Commission</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    <td style="width:33%" class="text-xl-left">
                                        <b>  {{$disp_month}} {{$disp_year}} {{$disp_half}} half </b>
                                    </td>
                                    <td class="text-xl-right">{{number_format($total_amount,2)}}</td>
                                    <td class="text-xl-right">{{number_format($total_commission,2)}}</td>
                                </tr>

                                </tbody>
                            </table>

                        </button>
                    </h2>
                </div>
                <div id="collapse_{{$i}}" class="collapse" aria-labelledby="heading_{{$i}}"
                     data-parent="#bonus_per_month">
                    <div class="card-body">
                        <table class="table table-bordered" style=" table-layout: fixed ; width: 100% ;">
                            <theader>
                                <tr>
                                    <th class="text-xl-left">Name</th>
                                    <th class="text-xl-left">Reference</th>
                                    <th class="text-xl-left">Salesorder</th>
                                    <th class="text-xl-left">Invoiced at</th>
                                    <th class="text-xl-left">Paid at</th>
                                    <th class="text-xl-right">Total Amount</th>
                                    <th class="text-xl-right">Untaxed Amount</th>
                                    <th class="text-xl-right">Bonus</th>
                                    <th class="text-xl-right">Commission</th>
                                    <th class="text-xl-right">Amount Due</th>

                                </tr>

                            </theader>
                            <tbody>
                            @for ($j = 0; $j < count($payments[$i]['payments']) ; $j++)

                                @php
                                    $comm_percent = $payments[$i]['payments'][$j]->comm_percent;
                                    $sales_person_id = $payments[$i]['payments'][$j]->sales_person_id;
                                    $invoice_date = $payments[$i]['payments'][$j]->invoice_date;
                                    $amount_due = $payments[$i]['payments'][$j]->amount_due;

                                    $bonus = number_format($comm_percent*100,2) . "%";

                                    if($amount_due <= 0.00){
                                         $amount_due = '';
                                     }
                                @endphp

                                <tr>
                                    <td class="text-xl-left">{{$payments[$i]['payments'][$j]->display_name}}</td>
                                    <td class="text-xl-left">{{$payments[$i]['payments'][$j]->move_name}}</td>
                                    <td class="text-xl-left">{{$payments[$i]['payments'][$j]->sales_order}}</td>
                                    <td class="text-xl-left">{{$payments[$i]['payments'][$j]->invoice_date}}</td>
                                    <td class="text-xl-left">{{$payments[$i]['payments'][$j]->payment_date}}</td>
                                    <td class="text-xl-right">{{number_format($payments[$i]['payments'][$j]->amount_taxed,2)}}</td>
                                    <td class="text-xl-right">{{number_format($payments[$i]['payments'][$j]->amount,2)}}</td>
                                    <td class="text-xl-right">{{$bonus}}</td>
                                    <td class="text-xl-right">{{number_format($payments[$i]['payments'][$j]->commission,2)}}</td>
                                    <td class="text-xl-right">{{$amount_due}}</td>
                                </tr>

                            @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
