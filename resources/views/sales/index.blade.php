@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card ">

                    @include ('sales.salespersons')
                    <div class="accordion" id="accordionExample">

                        @can('isAdmin')
                            @include('sales.w2_bonuses')
                            @include('sales.1099_bonuses')
                        @endcan

                        @canany(['isAdmin', 'isSalesPerson'])
                            @include('sales.aged_receivables')
                            @include('sales.customer_sales')
                            @include('sales.customer_invoices_select')
                            @include('sales.forecasts_select')
                        @endcanany

                        @can('isSalesPerson')
                        @endcan
                        @can('isAdmin')
                            @include('sales.bcc_datatables')
                            @include('sales.bcc_ext_datatables')
                            @include('sales.customer_statements')
                            @include('product_stock.zero_sellable_select')
                        @endcan
                    </div>
                </div>
                <div class="card-footer">
                    @include('global_footer')
                </div>
            </div>
        </div>
    </div>
@endsection
