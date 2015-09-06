<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">{{ Lang::get('rapport.rapporten') }}</h3>
    </div>

    <div class="panel-body">
        <ul class="rapport-files" data-trigger="rapport-list">
            @if(count($files))
                @foreach($files as $file)
                    <li>
                        <a title="{{ Lang::get('rapport.download') }}" href="{{ route('rapport.download', array($file)) }}">{{ $file }}</a>

                        <a class="btn btn-danger btn-sm" data-trigger="confirm" href="{{ route('rapport.delete', [$file]) }}">
                            <i class="fa fa-times"></i>&nbsp; verwijderen
                        </a>
                    </li>
                @endforeach
            @else
                <li>{{ Lang::get('rapport.no-files') }}</li>
            @endif
        </ul>
    </div>

</div>