@section('scripts')
    <script src="/js/inschrijving.index.min.js"></script>
@stop

@section('page-header')
    <?= Template::crumb([
            [
                    'text' => Lang::get('master.navs.gebruikers'),
            ],

            [
                    'text' => Lang::get('master.navs.inschrijvingen'),
            ]

    ]) ?>
@stop

@section('content')

    @include('layout.easy-search-top', ['view' => 'inschrijving.search', 'data' => $registrations])

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>
                    <div class="dropdown actions">
                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">{{ Lang::get('master.tools.acties') }}&nbsp;<span class="caret">&nbsp;</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="select-all" href="">{{ Lang::get('master.tools.select_all') }}</a></li>
                            <li><a class="select-none" href="">{{ Lang::get('master.tools.select_none') }}</a></li>
                            <li class="divider"></li>
                            <li><a class="remove" href="">{{ Lang::get('master.tools.remove') }}</a></li>
                        </ul>
                    </div>
                </th>
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
                    <td>
                        {{ $teller }} <input type="checkbox" value="{{ $registration->id }}"/>
                    </td>
                    <td>
                        <a href="<?= URL::route('inschrijvingen.edit', array($registration->id)) ?>">{{ $registration->firstname . ' ' . $registration->lastname}}</a>
                    </td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ $registration->organisation }}</td>
                    <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                </tr>

                <? $teller++ ?>
            @endforeach
            </tbody>
        </table>
    </div>

    @include('layout.easy-search-bottom', ['data' => $registrations])

@stop