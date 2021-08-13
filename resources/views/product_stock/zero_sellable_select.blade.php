<div class="card">
    <div class="card-header" id="headingSellable">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseSellable" aria-expanded="false"
                    aria-controls="collapseSellable">
                <h6>Stock Reports</h6>
            </button>
        </h2>
    </div>
    <div id="collapseSellable" class="collapse"
         aria-labelledby="headingSellable"
         data-parent="#accordionExample">
        <div class="card-body ">
            <div class="row">
                <div class="d-flex  justify-content-evenly">
                    <form method="post"
                          action="{{route('select.sellable')}}">
                        @csrf
                        <div class="form-group col-md-4">
                            <button type="submit" name="display" value="display"
                                    class="btn btn-primary">
                                Sellable Stock
                            </button>
                        </div>
                    </form>
                    <form method="post"
                          action="{{route('select.zero_sellable')}}">
                        @csrf
                        <div class="form-group col-md-4">
                            <button type="submit" name="display" value="display"
                                    class="btn btn-primary">
                                Stock at Zero
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
