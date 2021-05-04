@extends('layouts.app')

@section('content')
<div class="container">
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
                    @endcanany

                    @can('isSalesPerson')
                    @endcan
                    @can('isAdmin')
                    @include('sales.customer_statements')
                    @include('sales.margin_commissions')
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
