<div class="instrument-footer">

    <div class="row">

        @if($fullScreen)
            <div class="previous col-xs-3">
                <button data-trigger="previous-question" class="btn btn-{{ $panel->color }}"><i class="fa fa-arrow-left"></i>{{ Agent::isTablet() ? Lang::get('instrument.previous-question') : null}}</button>
            </div>
        @endif

        <div class="middle {{ $fullScreen ? 'col-xs-6' : 'col-xs-12' }}">
            <input type="hidden" id="next_panel" name="next_panel"/>
            <input type="submit" class="btn btn-{{ $panel->color ? $panel->color : 'primary' }}" value="{{ $next ? $next->title : Lang::get('instrument.bevestigen') }}">
        </div>

        @if($fullScreen)
            <div class="next col-xs-3">
                <button data-trigger="next-question" class="btn btn-{{ $panel->color }}">{{ Agent::isTablet() ? Lang::get('instrument.next-question'): null }}<i class="fa fa-arrow-right"></i></button>
            </div>
        @endif

    </div>
</div>