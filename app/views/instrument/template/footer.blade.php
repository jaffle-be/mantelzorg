<div class="instrument-footer">

    <div class="row">

        <div class="col-xs-12 col-sm-4">

        </div>

        <div class="col-xs-12 col-sm-4 text-center">
            <input type="hidden" id="next_panel" name="next_panel"/>
            <input type="submit" class="btn btn-{{ $panel->color ? $panel->color : 'primary' }}" value="{{ $next ? $next->title : Lang::get('instrument.bevestigen') }}">
        </div>

        <div class="col-xs-12 col-sm-4">

        </div>

    </div>
</div>