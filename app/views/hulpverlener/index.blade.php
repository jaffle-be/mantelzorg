@section('scripts')

    <script type="text/javascript" src="/js/hulpverleners.min.js"></script>

@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.gebruikers'),
            ),

            array(
                    'text' => Lang::get('master.navs.hulpverleners'),
            ),

    )) ?>
@stop

@section('content')

    @if($users->count())
        <div class="row easy-search">
            <div class="col-md-5">
                @include('hulpverlener.search')
            </div>
            <div class="col-md-7 text-right">
                {{ $users->links('pagination::simple') }}
            </div>
        </div>
    @else
        <div class="row easy-search">
            <div class="col-md-5">
                @include('hulpverlener.search')
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
                            <li><a class="regen-password" href="">{{ Lang::get('users.regen-password') }}</a></li>
                            <li class="divider"></li>
                            <li><a class="remove" href="">{{ Lang::get('master.tools.remove') }}</a></li>
                        </ul>
                    </div>
                </th>
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
                <td>{{ $teller }} <input type="checkbox" value="{{$user->id}}"/></td>
                <td>
                    <a href="<?= URL::action('HulpverlenerController@edit', array($user->id)) ?>">{{ $user->firstname . ' ' . $user->lastname}}</a>
                </td>
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

    @if($users->count())
        <div class="text-center">
            {{ $users->links() }}
        </div>
    @endif


@stop