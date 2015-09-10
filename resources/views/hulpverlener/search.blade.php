<form action="{{route('hulpverleners.index')}}" id="search">
    <div class="input-group">

        <div class="input-group-btn">
            <button class="btn btn-default">Zoeken</button>
        </div>

        <input class="form-control" placeholder="{{ Lang::get('master.tools.zoeken') }}" type="text" name="query" value="{{Input::get('query')}}"/>

        <div class="input-group-btn">
            <a class="btn btn-default" href="{{ route('hulpverleners.index') }}"><span class="fa fa-times-circle-o"></span></a>
        </div>
    </div>
    <input type="submit" class="hidden"/>
</form>