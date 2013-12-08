@section('content')

    <?= Template::crumb(array(
        array(
            'text' => Lang::get('master.navs.questionnaires'),
        ),

        array('text' => Lang::get('master.navs.overzicht'))
    )) ?>

<div class="questionnaires">

    @foreach($questionnaires as $questionnaire)
    <div class="row">
        <div class="col-md-4 questionnaire">
            <div class="header"><i class="glyphicons notes_2"></i><?= Lang::get('questionnaires.questionnaire') ?></div>
            <div class="body">
                <?= $questionnaire->title ?>
                @if($questionnaire->active === '1')
                <i class="glyphicons-icon ok_2">&nbsp;</i>
                @endif
            </div>
        </div>
        <div class="col-md-8 panels">
            <div class="header"><i class="glyphicons list"></i><?= Lang::get('questionnaires.panels') ?></div>
            <div class="body">
                <ul>
                @foreach($questionnaire->panels as $panel)
                    <li><a href=""><?= $panel->title ?></a></li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endforeach

</div>

<div class="page-actions">
    <button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i><?= Lang::get('questionnaires.new') ?></button>
</div>


<?= $questionnaireCreator ?>

@stop