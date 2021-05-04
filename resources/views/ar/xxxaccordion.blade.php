@extends('layouts.app')
@section('title', 'commissions Report')
@php
    $i=1;
@endphp
@section('content')
    <div class="container_fluid">
        <form method="get"
              action="{{route('ar_search',$rep_id)}}">
            @csrf

            <div class="form-row align-items-center mb-2">
                <div class="col-auto">
                    <input class="form-control" placeholder="Customer or Rep" name="customer"></input>
                </div>
                <div class="col-auto">
                    <button type="submit" name="display" value="display"
                            class="btn btn-primary">
                        Search
                    </button>
                </div>
                <div class="col-auto">
                    <a class="btn btn-primary" href="{{route('export_aged_ar')}}">
                        Export AR
                    </a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-primary" href="{{route('export_aged_ar_detail')}}">
                        Export AR SO
                    </a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-primary" href="{{route('export_ar_notes')}}">
                        Export Notes
                    </a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-success" href="{{route('home')}}">
                        Go Home
                    </a>
                </div>
            </div>
        </form>

        <div class="accordion" id="accordionExample">
            @php
                $loopCounter=0;
            @endphp
            @foreach ($ars_totals as $ars_total)
                <style>
                    .felon_color {
                    "color: #3ADF00; font-size: 100%";
                    }

                </style>
                <div class="card mb-1">
                    <div class="card-header" id="headingOne{{$i}}">
                        <p class="mb-0">
                        <table class="table table-bordered table-sm table-responsive-lg">
                            <thead>
                            <tr>
                                <th style="font-weight: normal" class="text-xl-left">Id</th>
                                <th style="font-weight: normal" class="text-xl-left">Customer</th>
                                <th style="font-weight: normal" class="text-xl-left">Rep</th>
                                <th style="font-weight: normal" class="text-xl-right">Not Today</th>
                                <th style="font-weight: normal" class="text-xl-right">7 days</th>
                                <th style="font-weight: normal" class="text-xl-right">14 days</th>
                                <th style="font-weight: normal" class="text-xl-right">30 days</th>
                                <th style="font-weight: normal" class="text-xl-right">60 days</th>
                                <th style="font-weight: normal" class="text-xl-right">90 days</th>
                                <th style="font-weight: normal" class="text-xl-right">120 days</th>
                                <th style="font-weight: normal" class="text-xl-right">Older</th>
                                <th style="font-weight: normal" class="text-xl-right">Residual</th>
                                <th style="font-weight: normal" class="text-xl-right">Grade</th>
                                <th style="font-weight: normal" class="text-xl-center">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $firstname = head(explode(' ', trim($ars_total->rep)));
                                $felon_color = '';
                                if($ars_total->is_felon == 0) {$felony_state = "ok"; $felon_color="color: #3ADF00; font-size:
                                100%";}
                                elseif ($ars_total->is_felon == 1) {$felony_state = "bad"; $felon_color="color: #FF8000; font-size:
                                110%";}
                                elseif ($ars_total->is_felon == 2) {$felony_state = "worse"; $felon_color="color: #FF0000;
                                font-size: 120%";}
                                elseif ($ars_total->is_felon == 3) {$felony_state = "worst"; $felon_color="color:#FF0040; font-size:
                                120%";}
                            @endphp
                            <tr>
                                <td id="customer_id{{$loopCounter}}">{{$ars_total->customer_id}}</td>

                                <td style="{{$felon_color}} class=" text-xl-left
                                "><b>{{substr($ars_total->customer,0,20)}}</b></td>
                                <td class="text-xl-left">{{$firstname}}</td>
                                <td class="text-xl-right">{{ $ars_total->range0 ? number_format($ars_total->range0,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range1 ? number_format($ars_total->range1,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range2 ? number_format($ars_total->range2,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range3 ? number_format($ars_total->range3,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range4 ? number_format($ars_total->range4,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range5 ? number_format($ars_total->range5,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range6 ? number_format($ars_total->range6,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->range7 ? number_format($ars_total->range7,2) : ''}}
                                </td>
                                <td class="text-xl-right">{{ $ars_total->residual ? number_format($ars_total->residual,2) : ''}}
                                </td>
                                <td id="is_felon_toggle{{$loopCounter}}" class="text-xl-right"><span
                                        id="felon_color{{$loopCounter}}"
                                        class="felon_color{{$loopCounter}}">{{ $felony_state}}</span>
                                </td>
                                <td class="text-xl-right">
                                    <!--collect button -->
                                    <button class="btn btn-sm btn-success" type="button" data-toggle="collapse"
                                            @php
                                                $collected='' ;

                                                $disp_notes=$notes->where('customer_id','=',$ars_total->customer_id);

                                            $note_count = $disp_notes->count();
                                            $note_age = 0;
                                            foreach($disp_notes as $disp_note){
                                            $created = new Carbon\Carbon($disp_note->updated_at);
                                            $now = Carbon\Carbon::now();
                                            $note_age = ($created->diff($now)->days);
                                            break;
                                            }

                                            if ($note_count)
                                            {
                                            if ($note_age <= 3)
                                                {$badge_class ="badge badge bg-danger";}
                                            else
                                                {$badge_class ="badge bg-light text-dark";}
                                            }
                                            else
                                            {
                                            $badge_class ="";
                                            $note_count = "";
                                            }

                                            $badge_class_collect ="";

                                            $collects= $amt_collects->where('customer_id','=',$ars_total->customer_id);
                                            foreach($collects as $collect){

                                            if($collect->amt_collected)
                                            {
                                            $badge_class_collect ="badge badge-bg-danger";
                                            $collected = '!';
                                            }
                                            else
                                            {
                                            $badge_class_collect ="";
                                            $collected = '';
                                            }
                                            break;
                                            }
                                            if($ars_total->is_felon == 0){
                                            $felon_button="btn btn-sm btn-success";
                                            }
                                            else{
                                            $felon_button="btn btn-sm btn-danger";
                                            }

                                            @endphp

                                            data-target="#collapseOne{{$i}}"
                                            aria-expanded="true" aria-controls="collapseOne{{$i}}">
                                        Orders
                                    </button>
                                    <button class="btn btn-sm btn-success" type="button" data-toggle="collapse"
                                            data-target="#collapseNote{{$i}}"
                                            aria-expanded="true" aria-controls="collapseNote{{$i}}">
                                        Notes <span class="{{$badge_class}}">{{$note_count}}</span>
                                    </button>
                                <!--                            <button class="btn btn-sm btn-success" type="button" data-toggle="collapse"
                                    data-target="#collapseCollect{{$i}}"
                                    aria-expanded="true" aria-controls="collapseCollect{{$i}}">
                                Collect <span class="{{$badge_class_collect}}">{{$collected}}</span>
                            </button>
-->
                                    <a href=""
                                       class="{{$felon_button}}" id="btn-submit{{$loopCounter}}" role="button"
                                       aria-pressed="false">
                                        Grade</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>


                        </p>
                    </div>

                    <div id="collapseOne{{$i}}" class="collapse" aria-labelledby="headingOne{{$i}}"
                         data-parent="#accordionExample">
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-sm table-responsive-lg">
                                <thead>
                                <tr>
                                    <th class="text-xl-left">Customer</th>
                                    <th class="text-xl-left">Order</th>
                                    <th class="text-xl-left">Original Rep</th>
                                    <th class="text-xl-right">Not Today</th>
                                    <th class="text-xl-right">7 days</th>
                                    <th class="text-xl-right">14 days</th>
                                    <th class="text-xl-right">30 days</th>
                                    <th class="text-xl-right">60 days</th>
                                    <th class="text-xl-right">90 days</th>
                                    <th class="text-xl-right">120 days</th>
                                    <th class="text-xl-right">Older</th>
                                    <th class="text-xl-right">Residual</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $ols = $ars->where('customer_id','=',$ars_total->customer_id);
                                    $k = 0;
                                @endphp

                                @foreach ($ols as $sl)
                                    @php
                                       $sales_order = substr($sl->sales_order,0,10)
                                    @endphp

                                    <tr>
                                        <td id="customer_id">{{$ars_total->customer_id}}</td>
                                        <td id="get_invoice{{$k++}}">{{$sales_order}}</td>
                                        <td>{{$sl->org_rep_name}}</td>
                                        <td class="text-xl-right">{{ $sl->range0 ? number_format($sl->range0,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range1 ? number_format($sl->range1,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range2 ? number_format($sl->range2,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range3 ? number_format($sl->range3,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range4 ? number_format($sl->range4,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range5 ? number_format($sl->range5,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range6 ? number_format($sl->range6,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->range7 ? number_format($sl->range7,2) : ''}}</td>
                                        <td class="text-xl-right">{{$sl->residual ? number_format($sl->residual,2) : ''}}</td>
                                    </tr>
                                    <script>

                                        $(document).on('click', '#get_invoice{{$k}}', function (event) {
                                            event.preventDefault();
                                            //     let href = $(this).attr('data-attr');
                                         //   alert("this works too {{$k}}");
                                            let sales_order = '{{$sales_order}}';
                                            let href_root = '/ar/ajax_invoice/';
                                            let href = href_root + sales_order;
                                        //    alert(href);
                                            $.ajax({
                                                url: href,
                                                beforeSend: function () {
                                                    $('#loader').show();
                                                },
                                                // return the result
                                                success: function (result) {
                                                    $('#mediumModal{{$k}}').modal("show");
                                                    $('#mediumBody{{$k}}').html(result).show();
                                                },
                                                complete: function () {
                                                    $('#loader').hide();
                                                },
                                                error: function (jqXHR, testStatus, error) {
                                                    console.log(error);
                                                    alert("Page " + href + " cannot open. Error:" + error);
                                                    $('#loader').hide();
                                                },
                                                timeout: 8000
                                            })
                                        });


                                    </script>
                                    <div class="modal fade" id="mediumModal{{$k}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-fullscreen">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Detail View</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-12">
                                                        <div id="mediumBody{{$k}}" class="row">
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
                    <div id="collapseNote{{$i}}" class="collapse" aria-labelledby="headingNote{{$i}}"
                         data-parent="#accordionExample">
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-sm table-responsive-lg">
                                <thead>
                                <tr>
                                    <th class="text-xl-center">Notes</th>
                                    <th class="text-xl-center">On</th>
                                    <th class="text-xl-center">By</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $disp_notes = $notes->where('customer_id','=',$ars_total->customer_id);
                                    $note_count = 0
                                @endphp
                                @if ($disp_notes)
                                    @foreach ($disp_notes as $note)
                                        <tr>
                                            <td>{{$note->note}}</td>
                                            <td> {{$note->updated_at}}</td>
                                            <td> {{$note->note_by}}</td>
                                        </tr>
                                    @endforeach

                                @endif
                                </tbody>
                            </table>
                            <form method="post" action="{{action('InvoiceNoteController@store')}}">
                                @csrf
                                <input hidden name="customer_id" value="{{$ars_total->customer_id}}">
                                <input hidden name="customer_name" value="{{$ars_total->customer}}">
                                {{ Form::hidden('url',URL::previous()) }}

                                <div class="form-group">
                                    <label for="Note">Enter new note</label>

                                    <input class="form-control" name="note" value="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="notes" value="notes"
                                            class="btn btn-success btn-sm text-xl-right">
                                        Add Note
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                    <!--clollect part below here -->
                </div>
                @php
                    $i++
                @endphp
                <script>
                    $(document).ready(function () {
                        $("#btn-submit{{$loopCounter}}").click(function (e) {
                            e.preventDefault();
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            var customer_id = $('#customer_id{{$loopCounter}}').text();
                            //    alert(customer_id);
                            $.ajax({

                                type: 'POST',

                                url: '/ajaxRequestPost',
                                data: {customer_id: customer_id},
                                success: function (result) {
                                    //         console.log(result);
                                    $('#btn-submit{{$loopCounter}}').removeClass().addClass(result.new);
                                    $('#is_felon_toggle{{$loopCounter}}').text(result.felony_state);
                                    $(document).ajaxComplete(function () {
                                        //     console.log(result.felony_color);
                                        // $('#felon_color{{$loopCounter}}').removeClass().addClass(result.felony_color);
                                    });
                                }
                            });

                        });
                        //       console.log(customer_id{{$loopCounter}});
                    })
                </script>
                @php
                    $loopCounter=$loopCounter+1
                @endphp

            @endforeach
        </div>
    </div>


@endsection
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

