@extends('layouts.app_datatables')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')

    <div class="container_fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <table id="data-table" class="table table-bordered table-responsive-md">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>License</th>
                                <th>City</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>At Oz</th>
                                <th>Salesperson</th>
                                <th>Total Sales</th>
                                <th>Last Invoice</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>City</th>
                                <th>County</th>
                                <th>Territory</th>
                                <th>At Oz</th>
                                <th>Salesperson</th>
                                <th>Invoice</th>
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

        $(document).ready(function () {
            $('#data-table tfoot th').each(function () {
                var title = $('#data-table tfoot th').eq($(this).index()).text();
                if (title != "") {
                    $(this).html('<input type="text" placeholder="' + title + '" />');
                }
            });


            var table = $('#data-table').DataTable({
                dom: 'B<"clear">flrtip',
                buttons:
                    [
                        {
                            extend: 'excelHtml5', title: 'Bcc Extended'
                        }
                    ],
                lengthMenu:
                    [
                        [-1, 10, 25, 50, -1],
                        ['Show all','10 rows', '25 rows', '50 rows']
                    ],

                processing: true,
                serverSide: true,

                "ajax": "{{'/ext_bcc'}}",
                columns:
                    [
                        {data: 'customer'},
                        {data: 'license'},
                        {data: 'city'},
                        {data: 'county'},
                        {data: 'territory'},
                        {data: 'at_oz'},
                        {data: 'sales_person'},
                        {data: 'total_sales'},
                        {data: 'last_invoice'}
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
            })
        });

    </script>
@endsection
