<ul class="nav nav-pills" style="margin-bottom: 10px;">
    <li role="presentation" class="{{ $active == 'insights' ? "active" : '' }}">
        <a href="{{ route('stats.insights') }}">{{ Lang::get('stats.insights') }}</a></li>
    <li role="presentation" class="{{ $active == 'activity' ? "active" : '' }}">
        <a href="{{ route('stats.activity') }}">{{ Lang::get('stats.activity') }}</a></li>
</ul>