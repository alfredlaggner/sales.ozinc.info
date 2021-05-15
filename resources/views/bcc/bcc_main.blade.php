@extends('layouts.app_datatables')

<meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <table id="data-table" class="table table-bordered table-responsive-md">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>License</th>
                                <th>City</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>At Oz</th>
                                <th>Name</th>
                                <th>Date Issued</th>
                                <th>Issued</th>
                                <th>Expire</th>
                            </tr>
                            </thead>
                        <tbody></tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>City</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>At Oz</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
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

        $(document).ready(function () {

            $('#data-table tfoot th').each(function () {
                var title = $('#data-table tfoot th').eq($(this).index()).text();
                if (title != "") {
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
            });

            var table = $('#data-table').DataTable({
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
                ajax: "{{ 'https://sales.ozinc.info/bcctest' }}",
                columns:
                    [
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "defaultContent": ''
                        },
                        {data: 'businessName', name: 'businessName', visible: true},
                        {data: 'licenseNumber', name: 'licenseNumber'},
                        {data: 'premiseCity', name: 'premiseCity', visible: true},
                        {data: 'premiseCounty', name: 'premiseCounty'},
                        {data: 'territory', name: 'territory'},
                        {
                            data: 'ozCustomer', "render": function (data, type, row) {
                                return data ? "Yes" : "No"
                            }
                        },
                        {data: 'businessDBA', name: 'businessDBA', visible: false},
                        {data: 'premiseZip', name: 'premiseZip', visible: false},
                        {data: 'issuedDate', name: 'issuedDate', visible: true},
                        {data: 'expiryDate', name: 'issuedDate', visible: true},
                    ],
                "initComplete": function (settings, json) {
                    // Apply the search
                    this.api().columns().every(function () {
                        var that = this;

                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                }
            });

            $('#data-table tbody').on('click', 'td.details-control', function () {
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
