@extends('layouts.app')
@section('title', 'commissions Report')
@php
    $i=1;
@endphp
@section('content')
    <div class="container-fluid" style="width: 70%;">
        <div class="card" style="width: 100%; text-align: center">
            <div class="card-header">
                <h5>{{$name}}</h5>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#bonus" class="nav-link active" data-toggle="tab">1099 Commissions</a>
                </li>
<!--                <li class="nav-item">
                    <a href="#unpaid" class="nav-link" data-toggle="tab">Open Invoices</a>
                </li>
-->            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="bonus">
                    @include('commissions.sp.bonus_1099')
                </div>
                <div class="tab-pane fade" id="unpaid">
                    @include('commissions.sp.unpaid')
                </div>
            </div>
@endsection
