<div class="card">
    <div class="card-header" id="headingSixPlus">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseSixPlus" aria-expanded="false"
                    aria-controls="collapseSixPlus">
                <h6>Margin Commissions for first half of 2020</h6>
            </button>
        </h2>
    </div>
    <div id="collapseSixPlus" class="collapse" aria-labelledby="headingSixPlus"
         data-parent="#accordionExample">
        <div class="card-body">
            <form method="post"
                  action="{{route('admin')}}">
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
