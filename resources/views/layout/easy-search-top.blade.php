<div class="row easy-search">
    <div class="col-xs-12 col-lg-6 col-lg-offset-2">
        @include($view)
    </div>
    <div class="col-xs-12 col-lg-4 text-right">
        @if($data->lastPage() > 1)
            <? simple_paginator($data) ?>
        @endif
    </div>
</div>
