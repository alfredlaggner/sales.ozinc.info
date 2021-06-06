@php
    $start  = date('Y-m-d', strtotime("last week"));
/*$date = strtotime("+7 day");
$end =  date('Y-m-d', $date);
*/
$end = date('Y-m-d', strtotime("this week + 14 days"));

$salespersons = $salesperson->toArray();
/*array_push($salespersons,['name' => 'All','sales_person_id' => 0]);
*/$sp = collect($salespersons);
//dd($salesperson);
@endphp
<div class="card">
    <div class="card-header" id="headingCustomerforecast">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseCustomerforecast" aria-expanded="false"
                    aria-controls="collapseCustomerforecast">
                <h6>Sales Forecasts</h6>
            </button>
        </h2>
    </div>
    <div id="collapseCustomerforecast" class="collapse"
         aria-labelledby="headingCustomerforecast"
         data-parent="#accordionExample">
        <div class="card-body">
            <form method="post"
                  action="{{route('forecasts')}}">
                @csrf
                @can('isAdmin')
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-4">
                            <label for="salesperson">Name:</label>
                            <select class="form-control" name="salesperson_id">

                                @for($i=0; $i < count($sp); $i++)
                                    @if ($sp[$i]['sales_person_id'] == $data['salesperson_id'])
                                        <option value="{{$sp[$i]['sales_person_id']}}" selected>{{$sp[$i]['name']}}
                                        </option>
                                    @else
                                        <option value="{{$sp[$i]['sales_person_id']}}">{{$sp[$i]['name']}}
                                        </option>
                                    @endif
                                @endfor
                            </select>
                            <label for="start">From:</label>
                            <input class="form-control" id="start" name="start" type="text" value="{{$start}}">
                            <label for="end">To:</label>
                            <input class="form-control" id="end" name="end" type="text" value="{{$end}}">

                        </div>
                    </div>
                @elsecan('isSalesPerson')
                    <input name="salesperson_id" type="hidden" value="{{$salesperson_id}}">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-4">
                            <label for="salesperson">Namex:</label>
{{--
                            <select class="form-control" name="salesperson_id">
                                @foreach($salesperson as $sp)
                                    @if ($sp->sales_person_id == $data['salesperson_id'])
                                        <option value="{{$sp->sales_person_id}}" selected>{{$sp->name}}
                                        </option>
                                    @else
                                        <option value="{{$sp->sales_person_id}}">{{$sp->name}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
--}}
                            <label for="start">From:</label>
                            <input class="form-control" id="start" name="start" type="text" value="{{$start}}">
                            <label for="end">To:</label>
                            <input class="form-control" id="end" name="end" type="text" value="{{$end}}">

                        </div>
                    </div>

                @endcan

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="form-group col-md-4">
                        <button type="submit" name="display" value="display"
                                class="btn btn-primary">
                            Ready set go
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
