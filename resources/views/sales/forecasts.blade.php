@extends('layouts.app_datatables')
@section('title', 'Customer Report')
@section('content')

    <!--Load the AJAX API-->



    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-md-12">

            <div class="card">
                <div class='card-header'>
                    <h5>Sales Forecast for <b>{{$rep_name}}</b></h5>
                    <h5>From <b>{{$from}}</b> to <b>{{$to}}</b></h5>
                    <table class="table  table-bordered table-condensed table-sm">
                        @for($i = 0; $i < count($totals); $i++)
                            <tr>
                                <td class="text-left">{{$totals[$i]['customers_term_name']}}</td>
                                <td class="text-right">{{number_format($totals[$i]['sum_term'],2)}}</td>
                            </tr>

                        @endfor
                        <tr>
                            <td class="text-left">Forecast Total</td>
                            <td class="text-right"><b>{{number_format($total[0]['total_sum'],2)}}</b></td>
                        </tr>
                    </table>

                </div>
                <div class="card card-body">

                    <div id="div12">
                    </div>
                    <table id="forecasts" class="table table-bordered table-hover table-sm">
                        <thead>
                        <tr>
                            <th class="text-xl-center">Action</th>
                            <th class="text-xl-center">Customer</th>
                            <th class="text-xl-center">Due Date</th>
                            <th class="text-xl-center">Effective Date</th>
                            <th class="text-xl-center">Term</th>
                            <th class="text-xl-center">Term Id</th>
                            <th class="text-xl-center">Amount</th>
                            <th class="text-xl-center">Customer Id</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="chart_div"></div>
        <script>
            $(document).ready(function () {
                var table = $('#forecasts').DataTable({
                        dom: 'B<"clear">flrtip',
                        buttons:
                            [
                                {extend: 'excelHtml5'}
                            ],
                        lengthMenu:
                            [
                                [-1, 10, 25, 50],
                                ['Show all', '10 rows', '25 rows', '50 rows',]
                            ],

                        "processing": true,
                        "serverSide": true,
                        "ajax": "{{'/forecasts_ajax'}}",
                        "columns": [
                            {
                                "className": 'details-control',
                                "orderable": false,
                                "data": null,
                                "defaultContent": ''
                            },
                            {"data": "name"},
                            {"data": "expected_date", visible: false},
                            {"data": "confirmation_date", visible: false},
                            {"data": "term"},
                            {"data": "term_id", visible: false},
                            {
                                "data": "amount",
                                render: $.fn.dataTable.render.number(',', '.', 2, ''),
                                className: "text-right"
                            },
                            {"data": "customer_id", visible: false},

                        ]
                    }
                );

                $('#forecasts tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(show_invoices(row.data())).show();
                        tr.addClass('shown');
                    }
                });
            });

            function show_invoices(rowData) {
                var div = $('<div/>')
                    .addClass('loading')
                    .text('Loading...');

                let customer_id = rowData.customer_id;
                let href = '/forecasts_salesorders_ajax/' + customer_id;

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
        </script>

        <!-- medium modal -->
        <!-- Full screen modal -->
    </div>
@endsection

