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
                                {{--
                                                                <th>Action</th>
                                --}}
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript">

        $(document).ready(function () {

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
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show all']
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
                    ]
            })
        });
    </script>
@endsection
