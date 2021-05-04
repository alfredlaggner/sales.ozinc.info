@extends('layouts.app')
@section('title', 'commissions Report')
@section('content')
    <div class="container">
        @include('commissions.paid',['status' => 'paid_out'])
    </div>
@endsection
