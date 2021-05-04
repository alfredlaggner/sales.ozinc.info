<div class="container">
    <h5 class="card-title">Bonuses</h5>
    <h6 class="card-subtitle mb-2 text-muted">{{$month}} {{$year}}</h6>
</div>
<div class="card-body">
    @foreach ($sps as $sp)
        <div class="card">
            <div class="card-header">
                <table>
                    <thead>
                    <tr>
                        <th class="text-xl-left">Salesperson</th>
                        <th class="text-xl-right">Total Untaxed</th>
                        <th class="text-xl-right">Total Commission</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $sp_totals = $totals->where('sales_person_id', $sp->sales_person_id);
                    @endphp
                    @foreach($sp_totals as $total)
                        <tr>
                            <td class="text-xl-left">
                                <b>{{$sp->name}}</b></td>
                            <td class="text-xl-right">{{number_format($total->sp_amount,2)}}</td>
                            <td class="text-xl-right">{{number_format($total->sp_commission,2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                    <tr>
                        <th class="text-xl-left">Name</th>
                        <th class="text-xl-left">Sales Order</th>
                        <th class="text-xl-left">Invoiced at</th>
                        <th class="text-xl-left">Paid at</th>
                        <th class="text-xl-right">Invoice Amount</th>
                        <th class="text-xl-right">Base Amount</th>
                        <th class="text-xl-right">Bonus %</th>
                        <th class="text-xl-right">Bonus Amount</th>

                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $sp_payments = $payments->where('sales_person_id', $sp->sales_person_id);
                    @endphp

                    @foreach($sp_payments as $payment)
                    @if($payment->commission >= 1)

                    @php
                    $invoice_date =  str_replace('-','/',substr($payment->invoice_date,5));

                            if ($payment->invoice_date >= env('BONUS_START'))
                             {
                                  $bonus = $payment->comm_percent*100;
                             }
                             else
                                 {
                                      if ($payment->sales_person_id == 73)
                                      {
                                          $bonus = '6.00';
                                      } else {
                                          $bonus = 'Margin';
                                      }
                                 }
                             $amount_due = $payment->amount_due;
                            if($payment->amount_due <= 0){
                                 $amount_due = '';
                             }

                        @endphp
                        <tr>
                            <td>{{$payment->customer->name}}</td>
                            <td>{{$payment->sales_order}}</td>
                            <td>{{$invoice_date}}</td>
                            <td>{{$payment_date}}</td>
                            <td>{{$payment->amount_taxed}}</td>
                            <td>{{$payment->amount}}</td>
                            <td>{{$bonus}}</td>
                            <td>{{$payment->commission}}</td>
                        </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

