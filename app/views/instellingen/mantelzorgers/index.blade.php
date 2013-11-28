@section('content')

<?= $subnav ?>

<div>
    <a class="btn btn-primary" href="<?= URL::action('Instelling\MantelzorgerController@create', array(Auth::user()->id)) ?>"><?= Lang::get('users.create_mantelzorger') ?></a>
</div>

@foreach($hulpverlener->mantelzorgers as $mantelzorger)
<div class="row mantelzorger">
    <div class="col-md-6">
        <div>
            <div class="pull-left">{{ $mantelzorger->firstname . ' ' . $mantelzorger->lastname }}</div>
            <span class="pull-right">{{ $mantelzorger->city }}</span>
            <div class="clearfix middle"></div>
            <div class="pull-left">{{ $mantelzorger->email }}</div>
            <span>{{ $mantelzorger->created_at->format('d/m/Y') }}</span>
        </div>
    </div>


</div>
@endforeach

@stop