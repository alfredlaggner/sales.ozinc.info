@extends('layouts.app_ajax')
@php
    $i=1;
@endphp
@section('content')
    <div class="container">
               <h5>{{$invoice->customer_name}}</h5>
               <h6>{{$invoice->customer->street}}</h6>
               <h6>{{$invoice->customer->city}}, {{$invoice->customer->zip}}</h6>
               <h6>{{$invoice->customer->email}}</h6>
               <h6>{{$invoice->customer->phone}}</h6>

        <h6>Invoice Date: {{$invoice->invoice_date}} </h6>

        <table class="table">
            <thead>
            <tr>
                <th>Item</th>
                <th>Amount</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>

            @foreach($invoice_lines as $iv)

                <tr>
                    <td>{{$iv->name}}</td>
                    <td>{{$iv->quantity}}</td>
                    <td>${{number_format($iv->price_subtotal,2)}}</td>
                </tr>

            @endforeach

            </tbody>
        </table>

    </div>

@endsection
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
</script>


