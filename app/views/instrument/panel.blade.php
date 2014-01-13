@section('scripts')

<script src="/js/instrument.panel.min.js"></script>

@stop

@section('content')

<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.instrument'),

    ),
    array(
        'text' => Lang::get('master.navs.start'),
        'href' => URL::route('instrument')
    ),
    array(
        'text' => Lang::get('instrument.panel')
    )

)) ?>

<?= Form::open(array('route' => array('instrument.panel.submit', $panel->id))) ?>

<?= InstrumentTool::header($questionnaire, $panel); ?>

<?= InstrumentTool::questions($panel) ?>

<?= InstrumentTool::footer($questionnaire, $panel) ?>

<? Form::close() ?>

@stop