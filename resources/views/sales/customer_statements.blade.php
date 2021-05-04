<div class="card">
    <div class="card-header" id="headingCustomerStatement">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseCustomerStatement" aria-expanded="false"
                    aria-controls="collapseCustomerStatement">
                <h6>Customer Statements</h6>
            </button>
        </h2>
    </div>
    <div id="collapseCustomerStatement" class="collapse"
         aria-labelledby="headingCustomerStatement"
         data-parent="#accordionExample">
        <div class="card-body">
            <form method="post"
                  action="{{route('notify_customer')}}">
                @csrf
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
