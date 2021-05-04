@extends('layouts.app')
@section('title', 'Bonus')
@php
$i=1;
@endphp
@section('content')
<div class="container">
    <form method="get">
        @csrf

        <div class="form-row align-items-center mb-2">
            <div class="col-auto">
                <a class="btn btn-primary" href="{{route('export_bonus')}}">
                    Export to Excel
                </a>
                <a class="btn btn-success" href="{{route('home')}}">
                    Go Home
                </a>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Bonuses</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{$monthName}} {{$year}}</h6>
        </div>
        <div class="card-body">
            <div class="accordion" id="bonuses">
                @php $i=1; @endphp
                @foreach ($sps as $sp)
                <div class="card">
                    <div class="card-header" id="headingThree{{$i}}">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse"
                                    data-target="#collapseThree{{$i}}" aria-expanded="false"
                                    aria-controls="collapseThree{{$i}}">

                                <table id="accounts" class="table table-bordered table-hover table-sm table-fixed">
                                    <thead>
                                    <tr>
                                        <th class="text-xl-left"></th>
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
                                        <td style="width:33%" class="text-xl-left"><b>{{str_pad( $sp->name, 20,'
                                                ')}}</b></td>
                                        <td class="text-xl-right">{{number_format($total->sp_amount,2)}}</td>
                                        <td class="text-xl-right">{{number_format($total->sp_commission,2)}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree{{$i}}" class="collapse" aria-labelledby="headingThree"
                         data-parent="#bonuses">
                        <div class="card-body">
                            </table>

                            <table id="accounts" class="table table-bordered table-hover table-sm">
                                <thead>
                                <tr>
                                    <th class="text-xl-left">Name</th>
                                    <!--                                            <th class="text-xl-left">Reference</th>
                                    -->
                                    <th class="text-xl-left">Salesorder</th>
                                    <th class="text-xl-left">Invoiced at</th>
                                    <th class="text-xl-left">Paid at</th>
                                    <th class="text-xl-right">Total Amount</th>
                                    <th class="text-xl-right">Untaxed Amount</th>
                                    <th class="text-xl-right">Bonus</th>
                                    <th class="text-xl-right">Commission</th>
                                    <!--                                            <th class="text-xl-right">Amount Due</th>
                                    -->                                        </tr>
                                </thead>
                                <tbody>
                                @php

                                $sp_payments = $payments->where('sales_person_id', $sp->sales_person_id);
                                @endphp

                                @foreach($sp_payments as $payment)

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
                                    if($payment->amount_due <= 0){
                                    $amount_due = '';
                                    }
                                    @endphp

                                <tr>
                                    <td class="text-xl-left">{{$payment->customer->name}}</td>
                                    <!--                                                <td class="text-xl-left">{{$payment->move_name}}</td>
                                    -->
                                    <td class="text-xl-left">{{$payment->sales_order}}</td>
                                    <td class="text-xl-left">{{$invoice_date}}</td>
                                    <td class="text-xl-left">{{$payment_date}}</td>
                                    <td class="text-xl-right">{{number_format($payment->amount_taxed,2)}}</td>
                                    <td class="text-xl-right">{{number_format($payment->amount,2)}}</td>
                                    <td class="text-xl-right">{{$bonus}}</td>
                                    <td class="text-xl-right">{{number_format($payment->commission,2)}}</td>
                                    <!--                                                <td class="text-xl-right">{{$amount_due}}</td>
                                    -->
                                </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @php $i++; @endphp

                @endforeach
            </div>
            @endsection
        </div>
    </div>

    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>

