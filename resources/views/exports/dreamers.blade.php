<div class="container">
    <div class="card">
        <div class='card-header'>
            <h5>Dreamers Sales </h5>
        </div>
        <div class="card card-body">
            <div id="div12">
            </div>
            <table id="accounts" class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th style="font-weight: normal" class="text-xl-left">Account</th>
                    <th style="font-weight: normal" class="text-xl-left">Email</th>
                    <th style="font-weight: normal" class="text-xl-right">Quantity</th>
                    <th style="font-weight: normal" class="text-xl-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sales_lines as $sl)
                    <tr>
                        <td class="text-left">{{$sl->customer_name}}</td>
                        <td class="text-left">{{$sl->customer_email}}</td>
                        <td style="text-align:right">{{$sl->customer_total_quantity}}
                        <td style="text-align:right">{{ number_format($sl->customer_total_amount,2)}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

