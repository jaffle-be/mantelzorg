@section('scripts')

<script src="/js/instrument.panel.min.js"></script>

@stop

@section('page-header')
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
@stop

@section('content')

<?= Form::open(array(
    'route' => array('instrument.panel.submit', $panel->id, $survey->id),
    'id' => 'panel-form'
)) ?>

<?= InstrumentTool::header($panel); ?>

<?= InstrumentTool::questions($panel, $survey) ?>

<?= InstrumentTool::footer($panel) ?>

<? Form::close() ?>

@stop