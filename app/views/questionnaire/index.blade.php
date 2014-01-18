@section('content')

    <?= Template::crumb(array(
        array(
            'text' => Lang::get('master.navs.questionnaires'),
        ),

        array('text' => Lang::get('master.navs.overzicht'))
    )) ?>

<div class="questionnaires">

    @foreach($questionnaires as $questionnaire)
    <div class="row" data-questionnaire-id="<?= $questionnaire->id ?>">
        <div class="col-xs-10 col-sm-4 questionnaire">
            <div class="header"><i class="glyphicons notes_2"></i><?= Lang::get('questionnaires.questionnaire') ?></div>
            <div class="body">
                <?= Form::text('title', $questionnaire->title, array('class' => 'form-control questionnaire-title')) ?>
            </div>
        </div>
        <div class="col-xs-2 col-sm-1 icons">
            <div class="header">
                @if($questionnaire->active === '1')
                <i class="glyphicon glyphicon-check"></i>
                @else
                <i class="glyphicon glyphicon-unchecked"></i>
                @endif
            </div>
            <div class="body">
                <i class="glyphicon glyphicon-floppy-saved fade"></i>
            </div>

        </div>
        <div class="col-xs-10 col-sm-5 panels">
            <div class="header">
                <span>
                    <i class="glyphicons list"></i><?= Lang::get('questionnaires.panels') ?>
                </span>
                <a class="btn btn-default pull-right" data-toggle="panel-creator"><i class="glyphicon glyphicon-plus"></i></a>
            </div>
            <div class="body">
                <ul class='sortable'>
                @foreach($questionnaire->panels as $panel)
                    <li data-questionnaire-panel-id="<?= $panel->id ?>" id="panel-<?= $panel->id?>">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="glyphicon glyphicon-move"></i></div>
                            <?= Form::text('title', $panel->title , array(
                                'class' => 'form-control questionnaire-panel-title',
                                'data-panel-weight' => $panel->panel_weight
                            )) ?>
                            <div class="input-group-btn colors">
                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" href=""><i class="panel-color <?= $panel->color ? 'panel-' . $panel->color : '' ?>">&nbsp;</i>&nbsp;<i class="caret"></i></button>
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
                                    <i class="glyphicon glyphicon-tag"></i>
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
                    <li><i class="glyphicon glyphicon-floppy-saved fade"></i></li>
                <? $teller++; ?>
                @endwhile
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

<?= $panelCreator ?>

@stop