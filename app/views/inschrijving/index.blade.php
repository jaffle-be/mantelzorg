@section('content')


<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.gebruikers'),
    ),

    array(
        'text' => Lang::get('master.navs.inschrijvingen'),
    )

)) ?>


@if($registrations->count())
<div class="text-center">
    {{ $registrations->links() }}
</div>
@endif


<table class="table table-striped table-hover">

    <thead>
    <tr>
        <th>&nbsp;</th>
        <th><?= Lang::get('users.naam') ?></th>
        <th><?= Lang::get('users.email') ?></th>
        <th><?= Lang::get('users.organisatie') ?></th>
        <th><?= Lang::get('users.created_at') ?></th>
    </tr>
    </thead>

    <tbody>
    <? $teller = 1 ?>
    @foreach($registrations as $registration)

    <tr>
        <td>{{ $teller }}</td>
        <td><a href="<?= URL::action('InschrijvingController@edit', array($registration->id)) ?>">{{ $registration->firstname . ' ' . $registration->lastname}}</a></td>
        <td>{{ $registration->email }}</td>
        <td>{{ $registration->organisation }}</td>
        <td>{{ $registration->created_at->format('d/m/Y') }}</td>
    </tr>

    <? $teller++ ?>
    @endforeach
    </tbody>
</table>

@if($registrations->count())
<div class="text-center">
    {{ $registrations->links() }}
</div>
@endif

@stop