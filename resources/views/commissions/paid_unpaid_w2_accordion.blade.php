@extends('layouts.app')
@section('title', 'commissions Report')
@php
    $i=1;
@endphp
@section('content')
    <div class="container-fluid" style="width: 70%;">
        <div class="card" style="width: 100%; text-align: center">
            <div class="card-header">
                <h4>{{$name}}</h4>
                <h5>Collected Invoices</h5>
            </div>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#bonus" class="nav-link active" data-toggle="tab">Bonus Commissions</a>
                </li>
                @if (! $ten_ninety_periods)
                    <li class="nav-item">
                        <a href="#margin" class="nav-link" data-toggle="tab">Margin Commissions</a>
                    </li>
                @endif
{{--

                <li class="nav-item">
                    <a href="#unpaid" class="nav-link" data-toggle="tab">Open Invoices</a>
                </li>

--}}

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="bonus">
                    @include('commissions.sp.bonus_w2')
                </div>
                <div class="tab-pane fade" id="margin">
                    @include('commissions.sp.margin')
                </div>
                <div class="tab-pane fade" id="unpaid">
                    @include('commissions.sp.unpaid')
                </div>
            </div>
@endsection
