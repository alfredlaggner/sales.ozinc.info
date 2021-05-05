@extends('layouts.app_datatables')

<meta name="csrf-token" content="{{ csrf_token() }}">
{{--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
--}}
@section('content')
    <style></style>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h5>{{$bcc_type}}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-responsive-md data-table">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>License</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>At Oz</th>
                                <th>Name</th>
                                <th>Date Issued</th>
                                <th>City</th>
                                <th>Zip</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript">
        function format(d) {
            // `d` is the original data object for the row
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                '<td>DBA:</td>' +
                '<td>' + d.businessDBA + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>Owner:</td>' +
                '<td>' + d.businessOwner + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>Street:</td>' +
                '<td>' + d.addressLine1 + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>City:</td>' +
                '<td>' + d.premiseCity + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>Zip:</td>' +
                '<td>' + d.premiseZip + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>Phone:</td>' +
                '<td>' + d.phone + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>eMail:</td>' +
                '<td>' + d.email + '</td>' +
                '</tr>' +

                '<tr>' +
                '<td>Issue/Exp:</td>' +
                '<td>' + d.issuedDate + '</td>' +
                '<td>' + d.expiryDate + '</td>' +

                '</tr>' +
                '</table>';
        }

        $(function () {
            var table = $('.data-table').DataTable({
                dom: 'B<"clear">flrtip',
                buttons:
                    [
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                            }
                        },
                        'colvis',
                    ],
                lengthMenu:
                    [
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show all']
                    ],

                processing: true,
                serverSide: true,
                ajax:
            "{{ 'https://sales.ozinc.info/bcctest' }}",
                columns
        :
            [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {data: 'businessName', name: 'businessName', visible: true},
                {data: 'licenseNumber', name: 'licenseNumber'},
                {data: 'premiseCounty', name: 'premiseCounty'},
                {data: 'territory', name: 'territory'},
                {
                    data: 'ozCustomer', "render": function (data, type, row) {
                        return data ? "Yes" : "No"
                    }
                },
                {data: 'businessDBA', name: 'businessDBA', visible: false},
                {data: 'issuedDate', name: 'issuedDate', visible: false},
                {data: 'premiseCity', name: 'premiseCity', visible: false},
                {data: 'premiseZip', name: 'premiseZip', visible: false},
            ]
        }
        )
            ;
            $('.data-table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });
        });
    </script>
@endsection
