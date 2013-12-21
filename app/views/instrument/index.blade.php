@section('content')

<?= Template::crumb(array(
    array('text' => Lang::get('master.navs.instrument')),
    array('text' => Lang::get('master.navs.start'))
)) ?>



<?= Form::open(array(
    'class' => 'form-horizontal',
    'route' => array('instrument.panel.submit', $questionnaire->panels->first()->id)
)); ?>

<div class="row">

    <p class="well">{{ Lang::get('instrument.introduction') }}</p>

    <div class="col-md-6">
        <?= Form::select(
            'mantelzorger', $hulpverlener->mantelzorgers->lists('fullname', 'id'), null, array('class' => 'form-control')
        ) ?>

        <?= Form::select('oudere', array(), null, array(
            'class' => 'form-control hide'
        )) ?>
    </div>

</div>

<div class="row page-actions">

    <div class="col-md-12">

        <input class="btn btn-primary" type="submit" value="<?= Lang::get('master.general.confirm') ?>"/>

    </div>

</div>

<?= Form::close() ?>


@stop