@extends('layout.admin.master')

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.questionnaires'),
                    'href' => route('survey.index'),
            ),
            array(
                    'text' => Lang::get('master.navs.panels'),

            ),
            array(
                    'text' => Lang::get('master.navs.questions'),
            ),
    ))?>

@stop

@section('content')

    <input name="panel-id" id="panel-id" type="hidden" value="<?= $panel->id ?>"/>

    <div class="questions">
        @foreach($panel->questions as $question)

            <div class="card shadow-z-1">
                <div class="card-body">
                    <div class="question row" data-question-id="<?= $question->id ?>">
                        <div class="left col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="title{{$question->id}}">
                                    <?= Lang::get('questionnaires.title') ?>
                                </label>
                                <input class="form-control" type="text" name="title" id="title{{$question->id}}" value="<?= $question->title ?>"/>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="question{{$question->id}}">
                                    <?= Lang::get('questionnaires.question') ?>
                                </label>
                                <textarea class="form-control" type="text" name="question" id="question{{$question->id}}"><?= $question->question ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="meta<?= $question->id ?>">
                                    <?= Lang::get('questionnaires.meta') ?>
                                </label>

                                <textarea class="form-control meta" type="text" name="meta" id="meta{{$question->id}}"><?= br2nl($question->meta) ?></textarea>

                            </div>

                        </div>
                        <div class="right col-md-offset-1 col-md-5">
                            <div class="header">
                                <?= Lang::get('questionnaires.settings') ?>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="control-label summary_question_control">
                                        <input type="checkbox" value="1" class="summary_question" <?= $question->summary_question == '1' ? 'checked' : '' ?>/>
                                        <?= Lang::get('questionnaires.summary_question') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="control-label explainable_control">
                                        <input type="checkbox" value="1" class="explainable" <?= $question->explainable == '1' ? 'checked' : '' ?>/>
                                        <?= Lang::get('questionnaires.explainable') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label class="control-label multiple_choise_control">
                                        <input type="checkbox" value="1" class="multiple_choise" <?= $question->multiple_choise == '1' ? 'checked' : '' ?>/>
                                        <?= Lang::get('questionnaires.multiple_choise_question') ?>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox" <?= $question->multiple_choise == '0' ? 'style="display:none;"' : '' ?>>
                                    <label class="control-label multiple_answer_control">
                                        <input type="checkbox" value="1" class="multiple_answer" <?= $question->multiple_answer == '1' ? 'checked' : '' ?>/>
                                        <?= Lang::get('questionnaires.multiple_answer_question') ?>
                                    </label>
                                </div>
                            </div>

                            <div class="choises" <?= $question->multiple_choise == '0' ? 'style="display:none;"' : '' ?>>
                                <div class="header">
                                    <span><?= Lang::get('questionnaires.choises') ?></span>
                                    <button class="pull-right btn btn-default add-choise"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="body">
                                    <ul class="sortable">
                                        @foreach($question->choises as $choise)
                                            <li data-choise-id="<?= $choise->id ?>" id="choise-<?= $choise->id?>">
                                                <div class="input-group">
                                                    <div class="input-group-addon handle">
                                                        <i class="fa fa-arrows"></i>
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

                    </div>
                </div>
            </div>

        @endforeach

        <a class="btn btn-fab btn-primary question-creator-trigger" data-toggle="tooltip" data-original-title="<?= Lang::get('questionnaires.new_question') ?>" href=""><i class="fa fa-plus"></i></a>

    </div>


    @include('modals.question-creator')

    @include('modals.choise-creator')

@stop
