<div class="card">
    <div class="card-header" id="headingCustomerSales">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseCustomerSales" aria-expanded="false"
                    aria-controls="collapseCustomerSales">
                <h6>AR Totals </h6>
            </button>
        </h2>
    </div>
    <div id="collapseCustomerSales" class="collapse"
         aria-labelledby="headingCustomerSales"
         data-parent="#accordionExample">
        <div class="card-body">
            <form method="post"
                  action="{{route('customers_per_rep')}}">
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
