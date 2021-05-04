@extends('layouts.app')
@section('title', 'Customer Report')
@section('content')
<div class="container">
    <div class="card">
        <div class='card-header'>
            <h5>Yearly Totals per Customer</h5>
        </div>
        <div class="card card-body">
            <div id="div12">
            </div>
            <table id="accounts" class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th class="text-xl-center">Year</th>
                    <th class="text-xl-center">Rep</th>
                    <th class="text-xl-center">Customer</th>
                    <th class="text-xl-center">Invoices</th>
                    <th class="text-xl-center">Sales</th>
                    <th class="text-xl-center">Overdue</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $invoice)
                @if($invoice->year)
                <tr>
                    <td class="">{{$invoice->year}}</td>
                    <td class="">{{$invoice->rep}}</td>
                    <td class="">{{$invoice->name}}</td>
                    <td class="">{{number_format($invoice->invoices,0)}}</td>
                    <td class="">${{number_format($invoice->amount,2)}}</td>
                    <td class="">{{$invoice->due > 1 ? '$' . number_format($invoice->due,2) : ''}}</td>
                </tr>
                @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

