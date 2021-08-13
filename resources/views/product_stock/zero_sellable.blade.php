<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
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
                        <h5>Products with Zero Quantities</h5>
                    </div>
                    <div class="card-body">
                        <table class="table" id="table_id">
                            <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Name</th>
                                <th>Zero Since</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    <div class="card-footer">
                        @include('global_footer')
                    </div>
                </div>
            </div>
        </div>
        <script>
            var backNext = [
                {
                    text: "&lt;",
                    action: function (e) {
                        // On submit, find the currently selected row and select the previous one
                        this.submit( function () {
                            var indexes = table.rows( {search: 'applied'} ).indexes();
                            var currentIndex = table.row( {selected: true} ).index();
                            var currentPosition = indexes.indexOf( currentIndex );

                            if ( currentPosition > 0 ) {
                                table.row( currentIndex ).deselect();
                                table.row( indexes[ currentPosition-1 ] ).select();
                            }

                            // Trigger editing through the button
                            table.button( 1 ).trigger();
                        }, null, null, false );
                    }
                },
                'Save',
                {
                    text: "&gt;",
                    action: function (e) {
                        // On submit, find the currently selected row and select the next one
                        this.submit( function () {
                            var indexes = table.rows( {search: 'applied'} ).indexes();
                            var currentIndex = table.row( {selected: true} ).index();
                            var currentPosition = indexes.indexOf( currentIndex );

                            if ( currentPosition < indexes.length-1 ) {
                                table.row( currentIndex ).deselect();
                                table.row( indexes[ currentPosition+1 ] ).select();
                            }

                            // Trigger editing through the button
                            table.button( 1 ).trigger();
                        }, null, null, false );
                    }
                }
            ];

            $(document).ready(function () {
                $('#table_id').DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: "{{ route('product.zero_sellable_ajax') }}",
                        columns: [
                            {data: 'brand'},
                            {data: 'name'},
                            {data: 'inventory_date'},
                        ],
                    dom:
                'Bflrtip',
                    buttons:
                [
                    'excel'

                ],
                    lengthMenu:
                [
                    [-1,10, 25, 50],
                    ['Show all','10 rows', '25 rows', '50 rows' ]
                ],
            }
            );
            });

        </script>

@endsection
