@extends('layouts.app_datatables')
@section('title', 'commissions Report')
@section('content')
    <div class="container">
        <div class="card">
            <div class='card-header'>
                <h5>{{$customer_name}}</h5>
                <p class="text-xl-right">Debit: {{number_format($total_amount,2)}}</p>
                <p class="text-xl-right">Credit: {{number_format($total_payment,2)}}</p>
                <p class="text-xl-right">Calculated Balance: {{number_format($total_payment - $total_amount,2)}}</p>
                <p class="text-xl-right">Amount Due: {{number_format($total_residual,2)}}</p>

            </div>
            <div class="card card-body">
                <div id="div12">
                </div>
                <form method="get">
                    @csrf

                    <table id="accounts" class="table table-bordered table-hover table-sm">
                        <thead>
                        <tr>
                            <th class="text-xl-left">Date</th>
                            <th class="text-xl-left">Invoiced</th>
                            <th class="text-xl-left">Paid</th>
                            <th class="text-xl-left">Name</th>
                            <th class="text-xl-left">Sales Order</th>
                            <th class="text-xl-right">Debit $</th>
                            <th class="text-xl-right">Credit $</th>
                            <th class="text-xl-right">Due $</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ledgers as $sl)
                            @php
                                //  dd($ledgers);
                            @endphp
                            <tr>
                                <td class="text-xl-left">{{$sl['date']}}</td>
                                <td class="text-xl-left">{{$sl['due']}}</td>
                                <td class="text-xl-right">{{$sl['payment_date'] != '0000-00-00' ? $sl['payment_date'] : ''}}</td>
                                <td class="text-xl-left">{{$sl['name']}}</td>
                                <td class="text-xl-left">{{$sl['sales_order']}}</td>
                                <td class="text-xl-right">{{$sl['amount'] ? number_format($sl['amount'],2) : ''}}</td>
                                <td class="text-xl-right">{{$sl['payment_amount'] ? number_format($sl['payment_amount'],2) : ''}}</td>
                                <td class="text-xl-right">{{$sl['residual'] ? number_format($sl['residual'],2) : ''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </form>

            </div>
            <script>
            </script>

            <script>
            </script>

        </div>
    </div>
    </div>
@endsection

