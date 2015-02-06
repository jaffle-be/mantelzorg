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

    @if($registrations->count())
        <div class="row easy-search">
            <div class="col-xs-5">
                @include('inschrijving.search')
            </div>
            <div class="col-xs-7 text-right">
                {{ $registrations->links('pagination::simple') }}
            </div>
        </div>
    @else
        <div class="row easy-search">
            <div class="col-xs-5">
                @include('inschrijving.search')
            </div>
        </div>
    @endif


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
                    <label class="checkbox-inline">
                        <input type="checkbox" value="{{ $registration->id }}"/>{{ $teller }}
                    </label>
                </td>
                <td>
                    <a href="<?= URL::action('InschrijvingController@edit', array($registration->id)) ?>">{{ $registration->firstname . ' ' . $registration->lastname}}</a>
                </td>
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