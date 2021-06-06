<div class="card">
    <div class="card-header" id="headingBccExtCustomer">
        <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button"
                    data-toggle="collapse"
                    data-target="#collapseBccExtCustomer" aria-expanded="false"
                    aria-controls="collapseBccExtCustomer">
                <h6>BCC Extended Licenses</h6>
            </button>
        </h2>
    </div>
    {{--
        @php    dd($bcc_types); @endphp
    --}}
    <div id="collapseBccExtCustomer" class="collapse"
         aria-labelledby="headingBccExtCustomer"
         data-parent="#accordionExample">
        <div class="card-body">
            <form method="post"
                  action="{{route('bcc.ext_bcc')}}">
                @csrf
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="form-group col-md-6">
                        <label for="bcc_types">Company Type:</label>
                        <select class="form-control" name="bcc_type">
                            @for($i = 0; $i < count($bcc_types); $i++)
                                <option value="{{$bcc_types[$i]}}">{{$bcc_types[$i]}}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

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
