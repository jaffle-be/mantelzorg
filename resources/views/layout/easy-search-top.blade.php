@if($data->count() && $data->lastPage() > 1)
    <div class="row easy-search">
        <div class="col-xs-12 col-md-5">
            @include($view)
        </div>
        <div class="col-xs-12 col-md-7 text-right">
            <? simple_paginator($data) ?>
        </div>
    </div>
@else
    <div class="row easy-search">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            @include($view)
        </div>
    </div>
@endif