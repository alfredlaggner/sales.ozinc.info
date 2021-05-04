@extends('layouts.app')
@section('title', 'commissions Report')
@section('content')
    {{--
        <link href="https://unpkg.com/tabulator-tables@4.2.3/dist/css/tabulator.min.css" rel="stylesheet">
    --}}
    <link href="https://unpkg.com/tabulator-tables@4.7.2/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.7.2/dist/js/tabulator.min.js"></script>

{{--
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.2.3/dist/js/tabulator.min.js"></script>
--}}
{{--
    <link href="{{ URL::asset('css/dist/css/bootstrap/tabulator_bootstrap4.min.css') }}" rel="stylesheet">
--}}
    {{--
        <link href="{{ URL::asset('css/dist/css/tabulator_modern.min.css') }}" rel="stylesheet">
    --}}
    <script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
    <script lang="javascript" src="/js/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.5/jspdf.plugin.autotable.js"></script>

    <div class="container">
        @if ($overview)
            <div class="card">
                <div class='card-header'>
                    <h5>Total Sales Per Rep</h5>
                    @if($header)
                        @php
                            $dt = new DateTime($header->created_at);
                            $tz = new DateTimeZone('America/Los_Angeles'); // or whatever zone you're after
                            $dt->setTimezone($tz)
                        @endphp
                        <h5> Saved 1099 Commissions </h5>

                        <p>Description: {{$header->description }}<br>
                            Comment: {{$header->comment }}<br>
                            Created at: {{$dt->format('Y-m-d H:i:s') }}<br>
                            Created by: {{$header->created_by }}<br>
                            Table Name: {{$header->name }}<br>
                            Month: {{$header->month }} - {{$header->half}} - {{$header->year}}<br>
                            Period: {{$header->start }} - {{$header->end}}</p>
                    @endif

                </div>
                <div class="card card-body">
                    <div class="'table-controls">
                        <button id="download-xlsx-2">Excel</button>
                        <button id="download-pdf-2">PDF</button>
                    </div>
                    <div id="salespersons" class="table-sm table-bordered"></div>
                </div>
                <script>
                    function paramLookup(cell) {
                        return cell.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }

                    var table1 = new Tabulator("#salespersons", {
                    //    height: 400, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
                        data:{!! $overview !!}, //assign data to table
                        responsiveLayout: true,
                        layout: "fitColumns", //fit columns to width of table (optional)
                        groupHeader: [
                            function (value, count, data) { //generate header contents for gender groups
                                return value;
                            },
                        ], columns: [ //Define Table Columns
                            //    {title:"Name", field:"salesperson_name", width:150},
                            /*
                                                    {title: "Salesperson", field: "salesperson_name", responsive:4, width: 150},
                            */
                            {title: "Rep", field: "rep", responsive: 1},
                            {
                                title: "Paid Invoices",
                                field: "amount_total",
                                hozAlign: "right",
                                responsive: 4,
                                formatter: "money",
                                topCalc: "sum",
                                topCalcFormatter: "money",
                                topCalcFormatterParams: {
                                    decimal: ".",
                                    thousand: ",",
                                    precision: 2,
                                }
                            },
                            {
                                title: "Comission",
                                field: "commission_total",
                                hozAlign: "right",
                                responsive: 4,
                                formatter: "money",
                                topCalc: "sum",
                                topCalcFormatter: "money",
                                topCalcFormatterParams: {
                                    decimal: ".",
                                    thousand: ",",
                                    precision: 2,
                                }
                            },
                        ],
                    });
                    //trigger download of data.xlsx file
                    $("#download-xlsx-2").click(function () {
                        table1.download("xlsx", "salespersons_commissions.xlsx", {sheetName: "All Salespersons"});
                    });
                    //trigger download of data.pdf file
                    $("#download-pdf-2").click(function () {
                        table1.download("pdf", "data.pdf", {
                            orientation: "portrait", //set page orientation to portrait
                            title: "Salespersons commissions", //add title to report
                        });
                    })
                </script>
            </div>
        @endif


        <div class="card">
            <div class='card-header'>
                @if($header)
                    @php
                        $dt = new DateTime($header->created_at);
                        $tz = new DateTimeZone('America/Los_Angeles'); // or whatever zone you're after
                        $dt->setTimezone($tz)
                    @endphp
                    <h5> Paid Invoices</h5>
                @else
                    <h5>Paid Invoices</h5>
                @endif
            </div>
            <div class="card card-body">
                <div class="table-controls">
                    <button id="download_so-xlsx">Excel</button>
                    <button id="download_so-pdf">PDF</button>
                </div>

                <div id="sales_orders" class="table-sm table-bordered"></div>
            </div>


            <script>
                function paramLookup(cell) {
                    return cell.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                var table = new Tabulator("#sales_orders", {
                    //     height: automatic, // set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
                    data:{!! $sales !!}, //assign data to table
                    responsiveLayout: false,
                    layout: "fitColumns", //fit columns to width of table (optional)
                    groupBy: ['rep'],
                    orderBy: ['rep'],
                    groupStartOpen: [true],
                    groupToggleElement: "header",
                    columnCalcs: "both",
                    groupHeader: [
                        function (value, count, data) { //generate header contents for gender groups
                            return value;
                        },
                    ], columns:
                        [
                            {
                                title: "Account",
                                field: "customer_name",
                                hozAlign: "left"
                            },
                            {
                                title: "Salesorder",
                                field: "sales_order",
                                hozAlign: "center"
                            },

                            {
                                title: "Invoiced at",
                                field: "invoice_date",
                                hozAlign: "center"
                            },
                            {
                                title: "Paid at",
                                field: "payment_date",
                                hozAlign: "center"
                            },
                            {
                                title: "Untaxed Amount",
                                field: "amount_untaxed",
                                hozAlign: "right",

                                topCalc: "sum",
                               formatter: "money",
                                formatterParams: "allowEmpty",
                                topCalcParams: {precision: 2},
                                topCalcFormatter: "money",
                                topCalcFormatterParams: {
                                    decimal: ".",
                                    thousand: ",",
                                    precision: 2,

                                }
                                                      },


                            {
                                title: "Bonus",
                                field: "comm_percent",
                                hozAlign: "right",
                                formatter: "money",
                                formatterParams: {
                                    symbol:" %",
                                    symbolAfter:"p"
                                }
                            },

                            {
                                title: "Commission",
                                field: "commission",
                                hozAlign: "right",
                                topCalc: "sum",
                                formatter: "money",
                                formatterParams: "allowEmpty",
                                topCalcParams: {precision: 2},
                                topCalcFormatter: "money",
                                topCalcFormatterParams: {
                                    decimal: ".",
                                    thousand: ",",
                                    precision: 2,
                                }
                            }
                        ],
                });
                //trigger download of data.xlsx file
                $("#download_so-xlsx").click(function () {
                    table.download("xlsx", "bi_monthly_commissions.xlsx", {sheetName: "All Salespersons"});
                });
                //trigger download of data.pdf file
                $("#download_so-pdf").click(function () {
                    table.download("pdf", "bi_monthly_commissions.pdf", {
                        orientation: "portrait", //set page orientation to portrait
                        title: "All Salespersons", //add title to report
                    });
                })
            </script>

        </div>
@endsection

