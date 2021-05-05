@extends('layouts.app')
<link rel="stylesheet" type="text/css"
      href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/datatables.min.css"/>
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-html5-1.7.0/b-print-1.7.0/r-2.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/datatables.min.js"></script>
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card ">
                    <div class="card-header">
                        <h5>BCC Retailer</h5>
                    </div>
                    <div class="card-body">
                        <table class="table" id="table_id">
                            <thead>
                            <tr>
                                <th>License</th>
                                <th>Issued</th>
                                <th>Name</th>
                                <th>DBA</th>
                                <th>City</th>
                                <th>Zip</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>Oz Customer</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($bccs as $bcc)

                                <tr>
                                    <td>{{$bcc->licenseNumber}}</td>
                                    <td>{{$bcc->issuedDate}}</td>
                                    <td>{{$bcc->businessName}}</td>
                                    <td>{{$bcc->businessDBA}}</td>
                                    <td>{{$bcc->premiseCity}}</td>
                                    <td>{{$bcc->premiseZip}}</td>
                                    <td>{{$bcc->premiseCounty}}</td>
                                    <td>{{$bcc->territory}}</td>
                                    <td>{{$bcc->ozCustomer ? "Yes" : "No"}}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>


                    </div>
                    <div class="card-footer">
                        @include('global_footer')
                    </div>
                </div>
            </div>
        </div>
        <script>

            $(document).ready(function () {
                $('#table_id').DataTable({
/*
                    serverSide: false,
                    ajax: "{{ route('bcc.datatables') }}",
                    columns: [
                        { name: 'id' },
                        { name: 'licenseNumber' },
                        { name: 'issuedDate' },
                        { name: 'businessName' },
                        { name: 'businessDBA' },
                        { name: 'premiseCity' },
                        { name: 'premiseZip' },
                        { name: 'premiseCounty' },
                        { name: 'territory' },
                        { name: 'ozCustomer' },
                        { name: 'ozCustomer ? "Yes" : "No"' },
/!*
                        { name: 'role.name', orderable: false },
                        { name: 'action', orderable: false, searchable: false }
*!/
                    ],
*/
                        dom: 'Bflrtip',
                        buttons: [
                            'copy', 'excel', 'pageLength'
                        ],
                        lengthMenu: [
                            [ 10, 25, 50, -1 ],
                            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],
                    }
                );
            });

        </script>

@endsection
