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
            <label for="title">
                <?= Lang::get('questionnaires.title') ?>
            </label>
            <input class="form-control" type="text" name="title" id="title" value="<?= $question->title ?>"/>

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

            <div class="checkbox" <?= $question->multiple_choise === '0' ? 'style="display:none;"' : '' ?>>
                <label>
                    <input type="checkbox" value="1" class="multiple_answer" <?= $question->multiple_answer === '1' ? 'checked': '' ?>/>
                    <?= Lang::get('questionnaires.multiple_answer_question') ?>
                </label>
            </div>

            <div>
                <label for="extra_info<?= $question->id ?>">
                    <?= Lang::get('questionnaires.meta') ?>
                </label>

                <textarea class="form-control meta" type="text"  name="meta" id="question"><?= $question->meta ?></textarea>

            </div>
        </div>

        <div class="clearfix"></div>

        <div class="choises col-md-6" <?= $question->multiple_choise === '0' ? 'style="display:none;"' : '' ?>>
            <div class="header">
                <span><?= Lang::get('questionnaires.choises') ?></span>
                <button class="pull-right btn btn-default add-choise"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
            <div class="body">
                <ul class="sortable">
                @foreach($question->choises as $choise)
                    <li data-choise-id="<?= $choise->id ?>" id="choise-<?= $choise->id?>">
                        <div class="input-group">
                            <div class="input-group-addon handle">
                                <i class="glyphicon glyphicon-move"></i>
                            </div>
                            <div class="input-group-addon"><?= Lang::get('questionnaires.choise-title') ?></div>
                            <input class="form-control name" type="text" value="<?= $choise->title ?>"/>
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
    <a class="btn btn-primary question-creator-trigger" href=""><?= Lang::get('questionnaires.new_question') ?></a>
</div>

<?= $questionCreator ?>

<?= $choiseCreator ?>

@stop