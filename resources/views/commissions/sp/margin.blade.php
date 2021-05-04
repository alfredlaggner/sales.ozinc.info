<h6>Margin Commissions</h6>
<div class="card-body">
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col">Month</th>
            {{--
                                    <th scope="col">Commission</th>
            --}}
            <th scope="col">Paid at</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @for ($k =0; $k < count($months); $k++)
            <tr>
                @php
                    $paid_month=$months[$k]['month_name'];
                    $paid_year=$months[$k]['year'];
                    $paid_date=str_replace("_", "-", substr($months[$k]['name'],14,10));
                @endphp
                <td>{{$paid_month}}  {{$paid_year}}</td>
                <td> {{$paid_date}}</td>
                <td><a class="btn btn-primary active" role="button" aria-pressed="true"
                       href="{{ route ('paid_out',['table_name' => $months[$k]['name'],'rep' => $months[$k]['rep'],'description' => $paid_date])}}">Details
                    </a>
                </td>
            </tr>
        @endfor
        </tbody>
    </table>
</div>

