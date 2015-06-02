@if($data->count() && $data->getLastPage() > 1)
    <div class="row easy-search">
        <div class="col-xs-12 col-md-5">
            @include($view)
        </div>
        <div class="col-xs-12 col-md-7 text-right">
            {{ $data->links('pagination::simple') }}
        </div>
    </div>
@else
    <div class="row easy-search">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            @include($view)
        </div>
    </div>
@endif