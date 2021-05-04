    @can('isAdmin')
        <div class="card-header text-center"><h3>Sales Apps</h3></div>
    @elsecan('isSalesPerson')
        <div class="card-header text-center"><h3>Dashboard for {{$salesperson_name}}</h3></div>
    @endcan
    <div class="card-body">
        @can('isAdmin')
            <h4 class="text-center">Sales per Salesperson</h4><br/>
        @elsecan('isSalesPerson')
            <h4 class="text-center">Commissions</h4><br/>
        @endcan

        <form method="post"
              action="{{route('unpaid_paid')}}">
            @csrf
            @can('isAdmin')
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
                    </div>
                </div>
            @elsecan('isSalesPerson')
                <input name="salesperson_id" type="hidden" value="{{$salesperson_id}}">
            @endcan
            <div class="row">
                <div class="col-md-4"></div>
                <div class="form-group col-md-4">
                    <button type="submit" name="display" value="display" class="btn btn-primary">
                        Ready set go
                    </button>
                </div>
            </div>
        </form>
