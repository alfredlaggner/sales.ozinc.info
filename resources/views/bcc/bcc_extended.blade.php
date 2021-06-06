@extends('layouts.app_datatables')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')

    <div class="container_fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <table id="data-table" class="table table-bordered table-responsive-md">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>License</th>
                                <th>City</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>At Oz</th>
                                <th>Salesperson</th>
                                <th>Total Sales</th>
                                <th>Last Invoice</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{$d->businessName}}</td>
                                    <td>{{$d->licenseNumber}}</td>
                                    <td>{{$d->premiseCity}}</td>
                                    <td>{{$d->premiseCounty}}</td>
                                    <td>{{$d->territory}}</td>
                                    <td>{{$d->ozCustomer}}</td>
                                    <td>{{$d->customer->sales_person}}</td>
                                    <td>{{number_format($d->invoices_amount_untaxed_sum,2)}}</td>
                                    <td>{{$d->last_invoice->invoice_date}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
