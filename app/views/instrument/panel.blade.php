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

@stop