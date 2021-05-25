    <div class="container">
    <table id="accounts" class="table table-bordered table-hover table-sm">
        <thead>
        <tr>
            <th style="font-weight: normal" class="text-xl-left">Order Number</th>
            <th style="font-weight: normal" class="text-xl-right">Effective Date</th>
            <th style="font-weight: normal" class="text-xl-right">Due</th>
            <th style="font-weight: normal" class="text-xl-right">Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($forcast_array as $so)
            <tr>
                <td class="text-xl-left">{{$so['name']}}</td>
                <td class="text-xl-right">{{$so['confirmation_date']}}</td>
                <td class="text-xl-right">{{$so['due_date']}}</td>
                <td class="text-xl-right">${{number_format($so['amount'],2)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

