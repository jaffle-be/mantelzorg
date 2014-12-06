@section('scripts')
<script src="/js/instrument.index.min.js"></script>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array('text' => Lang::get('master.navs.instrument')),
            array('text' => Lang::get('master.navs.start'))
    )) ?>
@stop

@section('content')

<?= Form::open(array(
    'class' => 'form-horizontal',
    'name' => 'instrument-persons',
    'method' => 'POST',
    'route' => 'instrument.submit'
)); ?>

<div class="row">

    <div class="well">{{ Lang::get('instrument.introduction') }}</div>

    <div class="col-md-6">
        <?= Form::select(
            'mantelzorger', array('' => Lang::get('instrument.kies_mantelzorger')) + $hulpverlener->mantelzorgers->sortBy(function($item){
                return $item->displayName;
            })->lists('displayName', 'id'), null, array(
                'id' => 'mantelzorger-select',
                'class' => 'form-control'
            )
        ) ?>

    </div>

    <div class="col-md-6">
        <?= Form::select('oudere', array(), null, array(
            'class' => 'form-control hide',
            'id' => 'ouderen-select'
        )) ?>
    </div>

</div>

<div class="row page-actions">
    <div class="col-md-12">
        <div class="alert alert-danger" style="display:none;"><?= Lang::get('instrument.need_persons_selected') ?></div>
    </div>
</div>

<div class="row page-actions">
    <div class="col-md-12">
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('master.general.confirm') ?>"/>
    </div>
</div>

<? if(count($surveys)): ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th><?= Lang::get('instrument.mantelzorger') ?></th>
            <th><?= Lang::get('instrument.oudere') ?></th>
            <th><?= Lang::get('instrument.created') ?></th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
<? foreach($surveys as $survey): ?>
        <tr>
            <td><?= $survey->mantelzorger->displayName ?></td>
            <td><?= $survey->oudere->displayName ?></td>
            <td><?= $survey->created_at->format('d/m/Y') ?></td>
            <td><a href="<?= URL::route('instrument.panel.get', array($survey->questionnaire->panels->first()->id, $survey->id)) ?>"><?= Lang::get('instrument.werkverder') ?></a></td>
        </tr>
<? endforeach; ?>
        </tbody>
    </table>
<? endif ?>

<?= Form::close() ?>


@stop