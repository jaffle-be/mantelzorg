<form action="{{route('dash')}}" method="GET">
    <div class="input-group">
        <input class="form-control" placeholder="{{ Lang::get('master.tools.zoeken') }}" type="text" name="query" value="{{Input::get('query')}}"/>

        <div class="input-group-btn">
            <a class="btn btn-default" href="{{ route('dash') }}"><span class="fa fa-times-circle-o"></span></a>
        </div>
    </div>
    <input type="submit" class="hidden"/>
</form>