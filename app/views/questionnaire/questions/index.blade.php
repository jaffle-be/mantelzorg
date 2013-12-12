@section('content')

<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.questionnaires'),
        'href' => URL::route('questionnaires.index')
    ),
    array(
        'text' => Lang::get('master.navs.panels'),

    ),
    array(
        'text' => Lang::get('master.navs.questions')
    )
))?>

@stop