@extends('layouts.app')
@section('content')
@section('title', 'Initialize Monthly Bonus')

@section('content')
<div class="container">
    <div class="card">
        <div class='card-header'>
            <h5>Bonuses for {{$month_name}} {{$year}}</h5>
        </div>
        <div class="card card-body">
            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            <form method="get" action="{{route('bonus_update')}}">
                @csrf
                <table id="accounts" class="table table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                        <th class="text-xl-ledt">Salesperson</th>
                        <th class="text-xl-left">New Bonus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bonuses as $bonus)
                    <input hidden name="bonus_id[]" value="{{$bonus->id}}">
                    <input hidden name="sales_person_id[]" value="{{$bonus->sales_person_id}}">
                    <input hidden name="month" value="{{$month}}">
                    <input hidden name="year" value="{{$year}}">
                    <input hidden name="half" value="{{$half ?? ''}}">
                    <input hidden name="display" value="{{'display'}}">
                    <tr>
                        <td class="text-xl-left">{{$bonus->sales_person_name}}</td>
                        <td class="text-xl-left"><input {{$read_only}} class="form-control" name="percent[]"
                                                        value="{{$bonus->bonus * 100}}"></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <table class="table table-sm">
                    <tr>
                        <td>
                            <button name="month_paid" value="0" class="btn btn-primary btn-sm btn-block"
                                    type={{$submit}}>Set monthly bonuses
                            </button>
                        </td>
                        <td>
                            <button name="month_paid" value="1" class="btn btn-primary btn-sm btn-block"
                                    type="submit">Mark bonuses as paid
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm btn-block" type="button"
                                    onclick="window.location='{{ route("bonus_commissions_2",['month' => $month, 'year' => $year]) }}'">View Bonuses
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm btn-block" type="button"b
                                    onclick="window.location='{{ route("home") }}'">Go Home
                            </button>
                        </td>
                    </tr>
                </table>


            </form>
        </div>
    </div>
</div>
@endsection
