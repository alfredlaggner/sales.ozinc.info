    <div class="container">
    <table id="accounts" class="table table-bordered table-hover table-sm">
        <thead>
        <tr>
            <th style="font-weight: normal" class="text-xl-left">Order Number</th>
            <th style="font-weight: normal" class="text-xl-left">Name</th>
            <th style="font-weight: normal" class="text-xl-right">Amount</th>
            <th style="font-weight: normal" class="text-xl-center">Invoice Date</th>
            <th style="font-weight: normal" class="text-xl-center">Due Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td class="text-xl-left">{{$invoice->sales_order}}</td>
                <td class="text-xl-left">{{$invoice->name}}</td>
                <td class="text-xl-right">{{$invoice->amount_untaxed}}</td>
                <td class="text-xl-center">{{$invoice->invoice_date}}</td>
                <td class="text-xl-center">{{$invoice->due_date}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

