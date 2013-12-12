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


<div class="questions">
    @foreach($panel->questions as $question)
    <div class="question">
        <div class="header"><?= $question->title ?></div>
        <div class="body">

        </div>
    </div>
    @endforeach
</div>

<div class="page-actions">
    <a class="btn btn-primary" href=""><?= Lang::get('questionnaires.new_question') ?></a>
</div>

@stop