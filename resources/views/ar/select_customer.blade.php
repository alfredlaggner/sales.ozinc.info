@extends('layouts.app')
@section('title', 'commissions Report')
@section('content')
    <div class="container">
        <div class="card">
            <div class='card-header'>
                <div class="col-auto">
                    <a class="btn btn-success" href="{{route('home')}}">
                        Go Home
                    </a>
                </div>

            </div>
            <div class="card card-body">
                <div id="div12">
                </div>
                <table id="accounts" class="table table-responsive table-bordered table-hover table-sm" >
                    <thead>
                    <tr>
                        <th class="text-xl-center">Id</th>
                        <th class="text-xl-center">Name</th>
                        <th class="text-xl-center">Due</th>
{{--
                        <th class="text-xl-center">Email</th>
--}}
                        <th class="text-xl-center">Telephone</th>
                        <th class="text-xl-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        @if ($customer->has('sales_lines'))
                            <tr>
                                <td class="">{{$customer->ext_id}}</td>
                                <td class="">{{$customer->name}}</td>
                                <td class="text-right">{{$customer->total_due ? number_format($customer->total_due,2) : ''}}</td>
{{--
                                <td class="">{{$customer->email}}</td>
--}}
                                <td class="">{{$customer->phone}}</td>
                                <td class=""><a href="{{route('customer_statement',[$customer->ext_id,$customer->name])}}"
                                                class="btn btn-success">Print Statement</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <p class="text-muted text-center">&copy;
                    @php
                        $copyYear = 2018; // Set your website start date
                        $curYear = date('Y'); // Keeps the second year updated
                        echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '')
                    @endphp
                    Oz Distribution, Inc.
                </p>

                <div class="col-auto">
                    <a class="btn btn-success" href="{{route('home')}}">
                        Go Home
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

