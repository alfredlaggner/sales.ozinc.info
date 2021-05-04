<div class="card-body">
    @include('commissions.paid',['status' => 'unpaid'])<
{{--
    <ul class="nav nav-pills justify-content-center" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="#profile" role="tab" data-toggle="tab">Open</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#buzz" role="tab" data-toggle="tab">Close</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active mt-3"
             id="profile">@include('commissions.paid',['status' => 'unpaid'])</div>
        <div role="tabpanel" class="tab-pane fade mt-3" id="buzz">Click 'Open' to display</div>
    </div>
--}}
</div>
