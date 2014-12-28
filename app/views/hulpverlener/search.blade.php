<form action="{{route('hulpverleners.index')}}">
    <div class="input-group">
        <input class="form-control" placeholder="{{ Lang::get('master.tools.zoeken') }}" type="text" name="query" value="{{Input::get('query')}}"/>

        <div class="input-group-btn">
            <a class="btn btn-default" href="{{ route('hulpverleners.index') }}"><span class="glyphicons remove"></span></a>
        </div>
    </div>
    <input type="submit" class="hidden"/>
</form>