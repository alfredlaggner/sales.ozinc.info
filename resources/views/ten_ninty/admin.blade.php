@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.3/dist/js/tabulator.min.js"></script>
    <link href="{{ URL::asset('css/dist/css/bootstrap/tabulator_bootstrap4.min.css') }}" rel="stylesheet">
    {{--
        <link href="{{ URL::asset('css/dist/css/tabulator_modern.min.css') }}" rel="stylesheet">
    --}}
    <script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.5/jspdf.plugin.autotable.js"></script>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

{{--
                    <div class="card-header text-center"><h3>1099 Commission Admin</h3>
                        <form method="post" action="{{route('create_saved_commissions_paid_1099')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="form-group col-md-4">
                                    <label for="year">Year:</label>
                                    <input class="form-control" name="year" value="{{$year}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="form-group col-md-4">
                                    <label for="pay_period">Pay Period:</label>
                                    <select class="form-control" name="pay_period">
                                        @for($i=0;$i< count($pay_periods);$i++)
                                            @if ($pay_periods[$i]['month'] == \Carbon\Carbon::now()->month-1)
                                                <option selected value="{{$pay_periods[$i]['id']}}">
                                                    <b>{{$pay_periods[$i]['month_name']}}</b>
                                                    Part {{$pay_periods[$i]['half']}}
                                                </option>
                                            @else
                                                <option value="{{$pay_periods[$i]['id']}}">
                                                    <b>{{$pay_periods[$i]['month_name']}}</b>
                                                    Part {{$pay_periods[$i]['half']}}
                                                </option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="form-group col-md-4">
                                    <button type="submit" name="display" value="display"
                                            class="btn btn-primary">
                                        Save commissions
                                    </button>
                                    <button type="button" name="display" value="display"
                                            onclick="window.location='{{ route("home") }}'"
                                            class="btn btn-success">
                                        Go Home
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
--}}

                    <div class="card-body">
                        <h4 class="text-center">Saved Commissions</h4><br/>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Description</th>
                                <th scope="col">Created by</th>
                                <th scope="col">Created on</th>
                                <th scope="col">Comment</th>
                                <th scope="col">For Month</th>
                                <th scope="col">Half</th>
                                <th scope="col">Year</th>
                                <th scope="col">Status</th>
                                <th scope="col">View</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Set paid</th>
                                <th scope="col">Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($commissions as $commission)
                                <tr>
                                    <td>{{ $commission->id }}</td>
                                    <td>{{ $commission->description }}</td>
                                    <td>{{ $commission->created_by }}</td>
                                    <td>{{ $commission->date_created }}</td>
                                    <td>{{ $commission->comment }}</td>
                                    <td>{{ $commission->month }}</td>
                                    <td>{{ $commission->half }}</td>
                                    <td>{{ $commission->year }}</td>
                                    <td>{{ $commission->is_commissions_paid ? "Paid" : "Open"}}</td>

                                    <td><a class="btn btn-primary"
                                           href="{{route('saved_commission_1099',['id' => $commission->id])}}"
                                           role="button">
                                            View
                                        </a>
                                    </td>
                                    <td><a class="btn btn-primary"
                                           href="{{route('edit_saved_commission_1099',['id' => $commission->id])}}"
                                           role="button">
                                            Edit
                                        </a>
                                    </td>
                                <!--                                    </td><td><a class="btn btn-primary {{ $commission->is_commissions_paid ? "disabled" : ""}}"
                                        href="{{route('pay_saved_commission_1099',['id' => $commission->id, 'table_name' =>$commission->name])}}"
                                        role="button" area-disabled = {{ $commission->is_commissions_paid ? "true" : "false"}}>
                                        Mark as paid
                                        </a>
                                    </td>
-->
                                    <td><a class="btn btn-primary"
                                           href="{{route('pay_saved_commission_1099',['id' => $commission->id, 'table_name' =>$commission->name])}}"
                                           role="button">
                                            Mark as paid
                                        </a>
                                    </td>
                                    <td><a class="btn btn-primary"
                                           href="{{route('remove_commission_1099',['id' => $commission->id, 'table_name' =>$commission->name])}}"
                                           role="button">
                                            Remove
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <p class="text-muted text-center">&copy;
                            @php
                                $copyYear = 2018; // Set your website start date
                                $curYear = date('Y'); // Keeps the second year updated
                                echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '');
                            @endphp
                            Oz Distribution, Inc.
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
