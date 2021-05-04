
<p class="text-muted text-center">
    &copy;
    @php
        $copyYear = 2018; // Set your website start date
        $curYear = date('Y'); // Keeps the second year updated
        echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '')
    @endphp
    Oz Distribution, Inc.
</p>

