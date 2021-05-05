@extends('layouts.app_datatables')
@section('title', 'Customer Report')
@section('content')
    <div class="container-float">
        <div class="col-md-12">

            <div class="card">
                <div class='card-header'>
                    <h6>Due Invoices for {{$rep_name[0]}}</h6>
                </div>
                <div class="card card-body">
                    <div id="div12">
                    </div>
                    <table id="accounts" class="table table-bordered table-hover table-sm">
                        <thead>
                        <tr>
                            <th class="text-xl-center">Action</th>
                            <th class="text-xl-center">Grade</th>
                            <th class="text-xl-center">Customer</th>
                            <th class="text-xl-center">Invoices</th>
                            <th class="text-xl-center">Sales</th>
                            <th class="text-xl-center">Overdue</th>
                            <th class="text-xl-center">Last Invoice</th>
                            <th class="text-xl-center">Last Payment</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            function format(rowData) {
                var div = $('<div/>')
                    .addClass('loading')
                    .text('Loading...');

                let customer_id = rowData.customer_id;
                let href_root = '/ar/ajax/';
                let href = href_root + customer_id;

                $.ajax({
                    url: href,
                    data: {},
                    success: function (result) {
                        div
                        $('.loading').html(result).show()
                        .removeClass('loading');
                    }
                });

                return div;
            }

            $(document).ready(function () {
                var table = $('#accounts').DataTable({
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

                        "processing": false,
                        "serverSide": true,
                        "order": [[1, 'asc']],
                        "ajax": "{{'https://sales.ozinc.info/arajax.customers'}}",
                        "columns": [
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "defaultContent": ''
                            },
                            {"data": "grade"},
                            {"data": "name"},
                            {"data": "invoices"},
                            {"data": "amount"},
                            {"data": "due"},
                            {"data": "invoice_date"},
                            {"data": "payment_date"},
                            {"data": "customer_id", visible: false}

                        ]
                    }
                );

                $('#accounts tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }
                });
            });


        </script>

        <!-- medium modal -->
        <!-- Full screen modal -->
    </div>
    </div>
    </div>
@endsection

