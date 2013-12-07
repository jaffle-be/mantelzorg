@section('content')

<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.instellingen'),
        'href' => Url::action('Instelling\PersonController@index')
    ),
    array(
        'text' => Lang::get('master.navs.mantelzorgers'),
    ),
    array(
        'text' => Lang::get('master.navs.overzicht')
    )
)) ?>

<div>
    <a class="btn btn-primary" href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.create', array($hulpverlener->id)) ?>"><?= Lang::get('users.create_mantelzorger') ?></a>
</div>

@foreach($hulpverlener->mantelzorgers as $mantelzorger)
<div class="row mantelzorger">
    <div class="col-md-3">
        <div class="header">
            <span><i class="glyphicons user"></i>{{ Lang::get('users.mantelzorger') }}</span>
        </div>
        <div>
            <a href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.edit', array($hulpverlener->id, $mantelzorger->id)) ?>">{{ $mantelzorger->firstname . ' ' . $mantelzorger->lastname }}<br/></a>
            {{ $mantelzorger->city }}<br/>
            {{ $mantelzorger->email }}<br/>
            {{ $mantelzorger->created_at->format('d/m/Y') }}<br/>
        </div>
    </div>

    <div class="col-md-9 ouderen">
        <div>
            <div class="header clearfix">
                <span class="pull-left"><i class="glyphicons parents"></i>{{ Lang::get('users.ouderen') }}</span>
                <a class="btn btn-default pull-right" href="<?= URL::action('Instelling\OudereController@create', array($mantelzorger->id)) ?>"><i class="glyphicon glyphicon-plus"></i></a>
            </div>

            <div>
                <ul>
                    @foreach($mantelzorger->oudere as $oudere)
                    <li>
                        <a href="<?= URL::route('instellingen.{mantelzorger}.oudere.edit', array($mantelzorger->id, $oudere->id)) ?>">{{ $oudere->firstname . ' ' . $oudere->lastname }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</div>
@endforeach

@stop