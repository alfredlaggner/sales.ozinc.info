@extends('layouts.app')
@section('title', 'Customer Report')
@section('content')
<div class="container">
    <div class="card">
        <div class='card-header'>
            <h5>Customer Totals for {{$rep_name}}</h5>
        </div>
        <div class="card card-body">
            <div id="div12">
            </div>
            <table id="accounts" class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th class="text-xl-center">Grade</th>
                    <th class="text-xl-center">Customer</th>
                    <th class="text-xl-center">Invoices</th>
                    <th class="text-xl-center">Sales</th>
                    <th class="text-xl-center">Overdue</th>
                    <th class="text-xl-center">Last Invoice</th>
                    <th class="text-xl-center">Last Payment</th>
                    <th class="text-xl-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                $i = 0;
                @endphp
                @foreach($customers as $customer)
                @php
                $i++;
                $felon_color = "";
                if($customer->grade == 0) {$felony_state = ""; $felon_color="color: #000000; font-size:
                100%";}
                elseif ($customer->grade == 1) {$felony_state = "bad"; $felon_color="color: #FF8000; font-size:
                100%";}
                elseif ($customer->grade == 2) {$felony_state = "worse"; $felon_color="color: #FF0000;
                font-size: 100%";}
                elseif ($customer->grade == 3) {$felony_state = "worst"; $felon_color="color:#FF0040; font-size:
                100%";}
                $customer_id = $customer->ext_id;

                $invoice_date = date('d-m-Y', strtotime($customer->invoice->invoice_date));
                if ($customer->payment){
                    $payment_date = date('d-m-Y', strtotime($customer->payment->payment_date));
                }
                else {
                   $payment_date = '';
                }
                @endphp

                @if($customer->year)

<!--                <tr data-toggle="modal" data-id=mediumButton{{$i}} data-target="#mediumModal{{$i}}">
-->                    <tr style="{{$felon_color}}">
                    <td style="{{$felon_color}}">{{$felony_state}}</td>
                    <td class=""> {{$customer->name}}</td>
                    <td class="text-right">{{number_format($customer->invoices,0)}}</td>
                    <td class="text-right">${{number_format($customer->amount,2)}}</td>
                    <td class="text-right">{{$customer->due > 1 ? '$' . number_format($customer->due,2) : ''}}</td>
                    <td class="text-center">{{$invoice_date}}</td>
                    <td class="text-center">{{ $payment_date }}</td>
                    <td class="text-center">
                        <button type="button" id="mediumButton{{$i}}" class="btn btn-sm btn-light" data-bs-toggle="modal"
                                data-bs-target="#mediumModal{{$i}}">
                            View Details

                    </td>

                </tr>
                @endif
                <script>

                    // display a modal (medium modal)
                    $(document).on('click', '#mediumButton{{$i}}', function (event) {
                        event.preventDefault();
                        //     let href = $(this).attr('data-attr');
                        let customer_id = '{{$customer_id}}';
                        let href_root = '/ar/ajax/';
                        let href = href_root + customer_id;
                        $.ajax({
                            url: href,
                            beforeSend: function () {
                                $('#loader{{$i}}').show();
                            },
                            // return the result
                            success: function (result) {
                                $('#mediumModal{{$i}}').modal("show");
                                $('#mediumBody{{$i}}').html(result).show();
                            },
                            complete: function () {
                                $('#loader{{$i}}').hide();
                            },
                            error: function (jqXHR, testStatus, error) {
                                console.log(error);
                                alert("Page " + href + " cannot open. Error:" + error);
                                $('#loader{{$i}}').hide();
                            },
                            timeout: 8000
                        })
                    });

                </script>
                <div class="modal fade" id="mediumModal{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel{{$i}}"
                     aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel{{$i}}">Detail View</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                    <div id="mediumBody{{$i}}" class="row">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- medium modal -->
    <!-- Full screen modal -->
</div>
</div>
</div>
@endsection

