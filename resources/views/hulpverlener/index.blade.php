@extends('layout.admin.master')

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

    @include('layout.easy-search-top', ['view' => 'hulpverlener.search', 'data' => $users])

    <div class="table-responsive">
        <table class="table table-striped table-hover">

            <thead>
            <tr>
                <th>
                    <div class="dropdown actions">
                        <a id="actions" class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">{{ Lang::get('master.tools.acties') }}&nbsp;<span class="caret">&nbsp;</span></a>
                        <ul class="dropdown-menu">
                            <li><a id="select-all" class="select-all" href="">{{ Lang::get('master.tools.select_all') }}</a></li>
                            <li><a id="select-none" class="select-none" href="">{{ Lang::get('master.tools.select_none') }}</a></li>
                            <li class="divider"></li>
                            <li><a id="regen-password" class="regen-password" href="">{{ Lang::get('users.regen-password') }}</a></li>
                            <li class="divider"></li>
                            <li><a id="remove" class="remove" href="#">{{ Lang::get('master.tools.remove') }}</a></li>
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
                    <td>
                        <div class="checkbox">
                            <label class="control-label" for="row{{$teller}}">
                                <input type="checkbox" id="row{{$teller}}" value="{{$user->id}}"/>
                                {{ $teller }}
                            </label>
                        </div>

                    </td>
                    <td>
                        <a href="<?= route('hulpverleners.edit', array($user->id)) ?>">{{ $user->firstname . ' ' . $user->lastname}}</a>
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
    </div>

    @include('layout.easy-search-bottom', ['data' => $users])


@stop