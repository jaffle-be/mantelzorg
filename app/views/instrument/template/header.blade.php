<div class="instrument-header  {{ 'panel-' . $panel->color }}">

    <h3 class="active-panel">{{ $panel->title }} <i class="pull-right fa fa-caret-down"></i></h3>

    <ul style="display: none;">
        @foreach($panel->questionnaire->panels as $item)
            <li class="{{ 'panel-' . $panel->color }}">
                <a data-target-id="{{ $item->id }}" href="">{{ $item->title }}</a>
            </li>
        @endforeach
    </ul>
</div>