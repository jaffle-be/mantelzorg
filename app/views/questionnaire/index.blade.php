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
        <div class="col-md-4 questionnaire">
            <div class="header"><i class="glyphicons notes_2"></i><?= Lang::get('questionnaires.questionnaire') ?></div>
            <div class="body">
                <?= Form::text('title', $questionnaire->title, array('class' => 'form-control questionnaire-title')) ?>
            </div>
        </div>
        <div class="col-md-1 icons">
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
        <div class="col-md-5 panels">
            <div class="header">
                <span>
                    <i class="glyphicons list"></i><?= Lang::get('questionnaires.panels') ?>
                </span>
                <a class="btn btn-default pull-right" data-toggle="panel-creator"><i class="glyphicon glyphicon-plus"></i></a>
            </div>
            <div class="body">
                <ul>
                @foreach($questionnaire->panels as $panel)
                    <li data-questionnaire-panel-id="<?= $panel->id ?>">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="glyphicon glyphicon-move"></i></div>
                            <?= Form::text('title', $panel->title , array(
                                'class' => 'form-control questionnaire-panel-title',
                                'data-panel-weight' => $panel->panel_weight
                            )) ?>
                            <div class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></div>
                        </div>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-2 savers">
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