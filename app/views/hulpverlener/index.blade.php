

@section('content')


<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.gebruikers'),
    ),

    array(
        'text' => Lang::get('master.navs.hulpverleners'),
    ),

)) ?>


<table class="table table-striped table-hover">

    <thead>
    <tr>
        <th>&nbsp;</th>
        <th><?= Lang::get('users.naam') ?></th>
        <th><?= Lang::get('users.email') ?></th>
        <th><?= Lang::get('users.organisatie') ?></th>
        <th><?= Lang::get('users.created') ?></th>
    </tr>
    </thead>

    <tbody>
    <? $teller = 1 ?>
    @foreach($users as $user)

    <tr>
        <td>{{ $teller }}</td>
        <td><a href="<?= URL::action('HulpverlenerController@edit', array($user->id)) ?>">{{ $user->firstname . ' ' . $user->lastname}}</a></td>
        <td>{{ $user->email }}</td>
        <td>
            @if($user->organisation)
            {{ $user->organisation->name }}
            @endif
        </td>
        <td>{{ $user->created_at->format('d/m/Y') }}</td>
    </tr>

    <? $teller++ ?>
    @endforeach
    </tbody>
</table>


@stop