

@section('content')

<? echo $subnav ?>

<table class="table table-striped table-hover">

    <thead>
    <tr>
        <th>&nbsp;</th>
        <th><?= Lang::get('hulpverleners.naam') ?></th>
        <th><?= Lang::get('hulpverleners.email') ?></th>
        <th><?= Lang::get('hulpverleners.organisatie') ?></th>
        <th><?= Lang::get('hulpverleners.created') ?></th>
    </tr>
    </thead>

    <tbody>
    <? $teller = 1 ?>
    @foreach($users as $user)

    <tr>
        <td>{{ $teller }}</td>
        <td><a href="<?= URL::action('HulpverlenerController@edit', array($user->id)) ?>">{{ $user->firstname . ' ' . $user->lastname}}</a></td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->organisation->name }}</td>
        <td>{{ $user->created_at->format('d/m/Y') }}</td>
    </tr>

    <? $teller++ ?>
    @endforeach
    </tbody>
</table>


@stop