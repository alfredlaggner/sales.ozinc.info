@php
    $start = date("Y-m-d");
$date = strtotime("+7 day");
$end =  date('Y-m-d', $date);
@endphp
<div class="card">
    <div class="card-header" id="headingCustomerForcast">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseCustomerForcast" aria-expanded="false"
                    aria-controls="collapseCustomerForcast">
                <h6>Sales Forcasts</h6>
            </button>
        </h2>
    </div>
    <div id="collapseCustomerForcast" class="collapse"
         aria-labelledby="headingCustomerForcast"
         data-parent="#accordionExample">
        <div class="card-body">
            <form method="post"
                  action="{{route('forcasts')}}">
                @csrf
                @can('isAdmin')
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-4">
                            {{--
                                                        <label for="salesperson">Name:</label>
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
                @elsecan('isSalesPerson')
                    <input name="salesperson_id" type="hidden" value="{{$salesperson_id}}">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="form-group col-md-4">
                            <label for="salesperson">Name:</label>
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
