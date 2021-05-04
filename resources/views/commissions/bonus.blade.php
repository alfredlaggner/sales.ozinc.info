@extends('layouts.app')
@section('title', 'commissions Report')
@section('content')

    <div class="container">
        <div class="card">
            <div class='card-header'>
                <h5>Bonuses for {{$month}} {{$year}}</h5>
            </div>
            <div class="card card-body">

                <table id="accounts" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                        <th class="text-xl-ledt">Salesperson</th>
                        <th class="text-xl-right">Total Untaxed</th>
                        <th class="text-xl-right">Total Commission</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($totals as $total)
                        <tr>
                            <td class="text-xl-left">{{$total->name}}</td>
                            <td class="text-xl-right">{{number_format($total->sp_amount)}}</td>
                            <td class="text-xl-right">{{number_format($total->sp_commission)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <table id="accounts" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                        <th class="text-xl-ledt">Salesperson</th>
                        <th class="text-xl-left">Salesorder</th>
                        <th class="text-xl-left">Invoiced at</th>
                        <th class="text-xl-right">Amount paid</th>
                        <th class="text-xl-right">Bonus</th>
                        <th class="text-xl-right">Commission</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        @php
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
                                         $bonus = 'C';
                                     }
                                }
                        @endphp
                        <tr>
                            <td class="text-xl-left">{{$payment->name}}</td>
                            <td class="text-xl-left">{{$payment->sales_order}}</td>
                            <td class="text-xl-left">{{$payment->invoice_date}}</td>
                            <td class="text-xl-right">{{number_format($payment->amount,2)}}</td>
                            <td class="text-xl-right">{{$bonus}}</td>
                            <td class="text-xl-right">{{number_format($payment->commission,2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
@endsection


