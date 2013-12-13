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

<input name="panel-id" id="panel-id" type="hidden" value="<?= $panel->id ?>"/>

<div class="questions">
    @foreach($panel->questions as $question)
    <div class="question row" data-question-id="<?= $question->id ?>">
        <div class="left col-md-6">
            <label for="question">
                <?= Lang::get('questionnaires.question') ?>
            </label>
            <textarea class="form-control" type="text" name="question" id="question"><?= $question->question ?></textarea>
        </div>
        <div class="right col-md-offset-1 col-md-5">
            <div class="header">
                <?= Lang::get('questionnaires.settings') ?>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" class="summary_question" <?= $question->summary_question === '1' ? 'checked': '' ?>/>
                    <?= Lang::get('questionnaires.summary_question') ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" class="explainable" <?= $question->explainable === '1' ? 'checked': '' ?>/>
                    <?= Lang::get('questionnaires.explainable') ?>
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" class="multiple_choise" <?= $question->multiple_choise === '1' ? 'checked': '' ?>/>
                    <?= Lang::get('questionnaires.multiple_choise_question') ?>
                </label>
            </div>
        </div>

        <div class="choises col-md-6">
            <div class="header">
                <span><?= Lang::get('questionnaires.choises') ?></span>
                <button class="pull-right btn btn-default add-choise"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
            <div class="body">
                <ul class="sortable">
                @foreach($question->choises as $choise)
                    <li data-choise-id="<?= $choise->id ?>">
                        <div class="input-group">
                            <div class="input-group-addon handle">
                                <i class="glyphicon glyphicon-move"></i>
                            </div>
                            <div class="input-group-addon"><?= Lang::get('questionnaires.choise-title') ?></div>
                            <input class="form-control name" type="text" value="<?= $choise->title ?>"/>
                            <div class="input-group-addon"><?= Lang::get('questionnaires.choise-value') ?></div>
                            <input class="form-control value" type="text" value="<?= $choise->value ?>"/>
                        </div>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>

    </div>
    @endforeach
</div>

<div class="page-actions">
    <a class="btn btn-primary" href=""><?= Lang::get('questionnaires.new_question') ?></a>
</div>

<?= $questionCreator ?>

<?= $choiseCreator ?>

@stop