<div class="card">
    <div class="card-header" id="bonus_Init2">
        <h2 class="mb-0">
            <button class="btn btn-link" type="button" data-toggle="collapse"
                    data-target="#collapseInit2" aria-expanded="false"
                    aria-controls="collapseInit2">
                <h6 class="text-center">W2 Bonuses</h6>
            </button>
        </h2>
    </div>

    <div id="collapseInit2" class="collapse" aria-labelledby="Init_commission"
         data-parent="#accordionExample">
        <div class="card-body">
            <div class="row">
                <div class="col text-center">
                    <h6>Set Bonus Percentages</h6>
                    <form method="post"
                          action="{{route('bonus_init')}}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="year">Year:</label>
                                <input class="form-control" name="year" type="text"
                                       value="{{$year}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="month">Month:</label>
                                <select class="form-control" name="month">
                                    @foreach($months as $sp)
{{--
                                        @if ($sp->month_id == $previous_month)
                                            <option value="{{$sp->month_id}}"
                                                    selected>{{$sp->name}} </option>
                                        @else
--}}
                                            <option
                                                value="{{$sp->month_id}}">{{$sp->name}} </option>
{{--
                                        @endif
--}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
{{--
                            <div class="col-6"></div>
--}}
                            <div class="form-group col-12">

                                <button type="submit" name="display" value="display"
                                        class="btn btn-primary">
                                    Ready set go
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col text-center">
                    <h6>View bonuses</h6>
                    <form method="post"
                          action="{{route('bonus_commissions')}}">
                        @csrf
                        <div class="row">
{{--
                            <div class="col-md-4"></div>
--}}
                            <div class="form-group col-12">
                                <label for="year">Year:</label>
                                <input class="form-control" name="year" type="text"
                                       value="{{$year}}">
                            </div>
                        </div>
                        <div class="row">
{{--
                            <div class="col-md-4"></div>
--}}
                            <div class="form-group col-12">
                                <label for="month">Month:</label>
                                <select class="form-control" name="month">
                                    @foreach($months as $sp)
                                        @if ($sp->month_id == $previous_month)
                                            <option value="{{$sp->month_id}}"
                                                    selected>{{$sp->name}} </option>
                                        @else
                                            <option
                                                value="{{$sp->month_id}}">{{$sp->name}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
{{--
                            <div class="col-6"></div>
--}}
                            <div class="form-group col-12">

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
    </div>
</div>
