@section('scripts')
<script src="/js/instrument.index.min.js"></script>
@stop

@section('content')

<?= Template::crumb(array(
    array('text' => Lang::get('master.navs.instrument')),
    array('text' => Lang::get('master.navs.start'))
)) ?>



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
                return $item->fullname;
            })->lists('fullname', 'id'), null, array(
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

<div class="page-actions">
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('master.general.confirm') ?>"/>
</div>

<?= Form::close() ?>


@stop