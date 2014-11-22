@section('content')

<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.instellingen'),
        'href' => URL::action('Instelling\PersonController@index')
    ),
    array(
        'text' => Lang::get('master.navs.mantelzorgers'),
    )

)) ?>

<div class="mantelzorgers">
    @foreach($hulpverlener->mantelzorgers as $mantelzorger)
    <div class="row mantelzorger">
        <div class="col-md-3">
            <div class="header">
                <span><i class="glyphicons user"></i>{{ Lang::get('users.mantelzorger') }}</span>
            </div>
            <div class="body">
                <a href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.edit', array($hulpverlener->id, $mantelzorger->id)) ?>">
                    <?= Form::text('name', $mantelzorger->displayName, array('class' => 'form-control')) ?>
                </a>
            </div>
        </div>

        <div class="col-md-5 col-md-offset-1 ouderen">
            <div>
                <div class="header clearfix">
                    <span class="pull-left"><i class="glyphicons parents"></i>{{ Lang::get('users.ouderen') }}</span>
                    <a class="btn btn-default pull-right" href="<?= URL::action('Instelling\OudereController@create', array($mantelzorger->id)) ?>"><i class="glyphicon glyphicon-plus"></i></a>
                </div>

                <div class="body">
                    <ul>
                        @foreach($mantelzorger->oudere as $oudere)
                        <li>
                            <a href="<?= URL::route('instellingen.{mantelzorger}.oudere.edit', array($mantelzorger->id, $oudere->id)) ?>">
                                <?= Form::text('name', $oudere->displayName, array('class' => 'form-control')) ?>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
    @endforeach
</div>

<div class="page-actions">
    <a class="btn btn-primary" href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.create', array($hulpverlener->id)) ?>"><i class="glyphicon glyphicon-plus"></i><?= Lang::get('users.create_mantelzorger') ?></a>
</div>


@stop