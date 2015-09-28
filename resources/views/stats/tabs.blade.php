<ul class="nav nav-pills" style="margin-bottom: 10px;">
    <li role="presentation" class="{{ $active == 'insights.ouderen' ? "active" : '' }}">
        <a href="{{ route('stats.insights.ouderen') }}">{{ Lang::get('stats.insights-ouderen') }}</a></li>
    <li role="presentation" class="{{ $active == 'insights.question' ? "active" : '' }}">
        <a href="{{ route('stats.insights.answers') }}">{{ Lang::get('stats.insights-answers') }}</a></li>
    <li role="presentation" class="{{ $active == 'activity' ? "active" : '' }}">
        <a href="{{ route('stats.activity') }}">{{ Lang::get('stats.activity') }}</a></li>
</ul>