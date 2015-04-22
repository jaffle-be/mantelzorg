@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.questionnaires'),
            ),

            array('text' => Lang::get('master.navs.overzicht'))
    )) ?>

@stop

@section('content')

    <div class="questionnaires">

        @foreach($questionnaires as $questionnaire)
            <div class="row" data-questionnaire-id="<?= $questionnaire->id ?>">
                <div class="col-xs-10 col-sm-4 questionnaire">
                    <div class="header">
                        <i class="fa fa-file-text-o"></i><?= Lang::get('questionnaires.questionnaire') ?></div>
                    <div class="body">
                        <?= Form::text('title', $questionnaire->title, array('class' => 'form-control questionnaire-title')) ?>
                    </div>
                </div>
                <div class="col-xs-2 col-sm-1 icons">
                    <div class="header">
                        @unless($questionnaire->panels->count() == 0)
                            <i data-trigger="deactivate" style="display:{{ $questionnaire->active ? "block" : "none" }};" class="fa fa-check-square-o"></i>
                            <i data-trigger="activate" style="display:{{ !$questionnaire->active ? "block" : "none" }};" class="fa fa-square-o"></i>
                        @endunless
                    </div>
                    <div class="body">
                        <i class="fa fa-cloud-upload fade"></i>
                    </div>

                </div>
                <div class="col-xs-10 col-sm-5 panels">
                    <div class="header">
                <span>
                    <i class="fa fa-list-ul"></i><?= Lang::get('questionnaires.panels') ?>
                </span>
                        <a class="btn btn-default pull-right" data-toggle="panel-creator"><i class="fa fa-plus"></i></a>
                    </div>
                    <div class="body">
                        <ul class='sortable'>
                            @foreach($questionnaire->panels as $panel)
                                <li data-questionnaire-panel-id="<?= $panel->id ?>" id="panel-<?= $panel->id?>">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-arrows"></i></div>
                                        <?= Form::text('title', $panel->title, array(
                                                'class'             => 'form-control questionnaire-panel-title',
                                                'data-panel-weight' => $panel->panel_weight
                                        )) ?>
                                        <div class="input-group-btn colors">
                                            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" href="">
                                                <i class="panel-color <?= $panel->color ? 'panel-' . $panel->color : '' ?>">&nbsp;</i>&nbsp;<i class="caret"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="panel-color panel-purple">&nbsp;</a></li>
                                                <li><a href="#" class="panel-color panel-blue">&nbsp;</a></li>
                                                <li><a href="#" class="panel-color panel-red">&nbsp;</a></li>
                                                <li><a href="#" class="panel-color panel-orange">&nbsp;</a></li>
                                                <li><a href="#" class="panel-color panel-yellow">&nbsp;</a></li>
                                                <li><a href="#" class="panel-color panel-green">&nbsp;</a></li>
                                                <li><a href="#" class="panel-color panel-gray">&nbsp;</a></li>
                                            </ul>
                                        </div>
                                        <div class="input-group-btn">
                                            <a href="<?= URL::route('panels.{panel}.questions.index', array($panel->id)) ?>" class="btn btn-default">
                                                <i class="fa fa-tag"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xs-2 col-sm-2 savers">
                    <div class="header">&nbsp;</div>
                    <div class="body">
                        <ul>
                            <? $teller = 1; ?>
                            @while($teller <= count($questionnaire->panels))
                                <li><i class="fa fa-cloud-upload fade"></i></li>
                                <? $teller++; ?>
                            @endwhile
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="page-actions">
        <p>
            <button class="btn btn-primary"><i class="fa fa-plus"></i><?= Lang::get('questionnaires.new') ?>
            </button>
        </p>
    </div>


    <?= $questionnaireCreator ?>

    <?= $panelCreator ?>

@stop