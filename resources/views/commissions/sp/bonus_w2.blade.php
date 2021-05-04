<h6> Bonus Commissions</h6>
<div class="card-body">
    @php
    $i = 1;
    @endphp

    <div class="accordion" id="bonus_per_month">
        @foreach($totals as $total)
        @php
        $disp_month = date("F", mktime(0, 0, 0, substr($total->summary_year_month, 4, 2), 1));
        $disp_year = substr($total->payment_date, 0, 4);
        @endphp
        <div class="card">
            <div class="card-header" id="heading_{{$i}}">
                <h2 class="mb-0 d-inline">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapse_{{$i}}" aria-expanded="false"
                            aria-controls="collapse_{{$i}}">
                        <table id="accounts" class="table table-bordered table-hover table-sm table-fixed"
                               style=" table-layout: fixed ; width: 100% " ;>
                            <thead>
                            <tr>
                                <th style="width:33%" class="text-xl-left"></th>
                                <th class="text-xl-right">Total Untaxed</th>
                                <th class="text-xl-right">Total Commission</th>
                            </tr>
                            </thead>

                            <tbody>
                            {{--
                            <tr>
                                <tdstyle
                                ="width:33%" class="text-xl-left"><b>{{$disp_month}} {{$disp_year}}</b></td>
                                <td style="width: 33% ;"
                                    align="right">{{number_format($total->sp_amount,2)}}
                                </td>
                                <td style="width: 33% ;"
                                    align="right">{{number_format($total->sp_commission,2)}}
                                </td>
                            </tr>
                            --}}
                            <tr>
                                <td style="width:33%" class="text-xl-left"><b>{{$disp_month}} {{$disp_year}}</b>
                                </td>
                                <td class="text-xl-right">{{number_format($total->sp_amount,2)}}</td>
                                <td class="text-xl-right">{{number_format($total->sp_commission,2)}}</td>
                            </tr>

                            </tbody>
                        </table>

                    </button>
                </h2>
            </div>
            <div id="collapse_{{$i}}" class="collapse" aria-labelledby="heading_{{$i}}"
                 data-parent="#bonus_per_month">
                <div class="card-body">
                    <table class="table table-bordered table-sm" <!--style=" table-layout: fixed ; width: 100% ;"-->>
                        <theader>
                            <tr>
                                <th class="text-xl-left">Name</th>
                                <!--                                    <th class="text-xl-left">Reference</th>
                                -->
                                <th class="text-xl-left">Salesorder</th>
                                <th class="text-xl-left">Invoiced at</th>
                                <th class="text-xl-left">Paid at</th>
                                <th class="text-xl-right">Total Amount</th>
                                <th class="text-xl-right">Untaxed Amount</th>
                                <th class="text-xl-right">Bonus</th>
                                <th class="text-xl-right">Commission</th>
                                <!--                                    <th class="text-xl-right">Amount Due</th>
                                -->
                            </tr>

                        </theader>
                        <tbody>
                        @php
                        $payment_per_month = $payments->where('month_paid',$total->month_paid);

                        @endphp
                        @foreach($payment_per_month as $payment)
                        @if($payment->commission >= 1)


                        @php
                        $payment_date = str_replace('-','/',substr($payment->payment_date,5));
                        $invoice_date =  str_replace('-','/',substr($payment->invoice_date,5));

                        if ($payment->invoice_date >= env('BONUS_START'))
                        {
                        $bonus = number_format($payment->comm_percent*100,2) . "%";
                        }
                        else
                        {
                        if ($payment->sales_person_id == 73)
                        {
                        $bonus = '6%';
                        } else {
                        $bonus = 'Margin';
                        }
                        }
                        $amount_due = $payment->amount_due;
                        if($payment->amount_due <= 0.00){
                        $amount_due = '';
                        }
                        @endphp
                        <tr>
                            <td class="text-xl-left">{{$payment->customer->name}}</td>
                            <!--                                    <td class="text-xl-left">{{$payment->move_name}}</td>
                            -->
                            <td class="text-xl-left">{{$payment->sales_order}}</td>
                            <td class="text-xl-left">{{$invoice_date}}</td>
                            <td class="text-xl-left">{{$payment_date}}</td>
                            <td class="text-xl-right">{{number_format($payment->amount_taxed,2)}}</td>
                            <td class="text-xl-right">{{number_format($payment->amount,2)}}</td>
                            <td class="text-xl-right">{{$bonus}}</td>
                            <td class="text-xl-right">{{number_format($payment->commission,2)}}</td>
                            <!--                                    <td class="text-xl-right">{{$amount_due}}</td>
                            -->                                </tr>
                        @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php
        $i++
        @endphp
        @endforeach
    </div>
</div>
