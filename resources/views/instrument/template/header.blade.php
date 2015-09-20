@section('page-header-wrapper')

    @if(!UI::isTablet() && !UI::isMobile())
        @parent
    @endif

    <div class="instrument-header shadow-z-1 {{ 'panel-' . $panel->color }}">

        <div class="heading">
            <h3 class="active-panel">{{ $panel->title }} <i class="pull-right fa fa-caret-down"></i></h3>
        </div>

        <ul style="display: none;">
            @foreach($panel->questionnaire->panels as $item)
                <li class="{{$item->id == $panel->id ? 'active' : null}}">
                    <a data-target-id="{{ $item->id }}" href="">{{ $item->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@overwrite