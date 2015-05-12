@if($data->count())
    <div class="row easy-search">
        <div class="col-xs-12 col-sm-5">
            @include($view)
        </div>
        <div class="col-xs-12 col-sm-7 text-right">
            {{ $data->links('pagination::simple') }}
        </div>
    </div>
@else
    <div class="row easy-search">
        <div class="col-xs-5">
            @include($view)
        </div>
    </div>
@endif