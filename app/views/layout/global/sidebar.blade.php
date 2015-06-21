<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse" id="sidebar">
        <ul class="nav" id="side-menu">

            @if($user->admin == 1)
                <li>
                    <a href="#"><span class="fa fa-users"></span>&nbsp;{{ Lang::get('master.navs.gebruikers') }}
                        <span class="fa arrow"></span></a>

                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="{{ route('hulpverleners.index') }}">{{ Lang::get('master.navs.hulpverleners') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('inschrijvingen.index') }}">{{ Lang::get('master.navs.inschrijvingen') }}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('questionnaires.index') }}"><span class="fa fa-file-text-o"></span>&nbsp;{{ Lang::get('master.navs.questionnaires') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('rapport.index') }}"><span class="fa fa-folder-open"></span>&nbsp;{{ Lang::get('master.navs.rapport') }}</a>
                </li>

                @if($user->id == 1)
                    <li>
                        <a href="{{ route('stats.index') }}"><i class="fa fa-line-chart"></i>&nbsp;{{ Lang::get('master.navs.stats') }}</a>
                    </li>
                @endif
            @endif

            <li>
                <a href="{{ route('dash') }}"><span class="fa fa-list-alt"></span>&nbsp;{{ Lang::get('master.navs.instrument') }}
                </a>
            </li>
            <li>
                <a href="#"><span class="fa fa-wrench"></span>&nbsp;{{ Lang::get('master.navs.instellingen') }}<span class="fa arrow"></span></a>

                <ul class="nav nav-second-level  collapse">
                    <li>
                        <a href="{{ route('instellingen.index') }}">{{ Lang::get('master.navs.profiel') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('instellingen.{hulpverlener}.mantelzorgers.index', array($user->id)) }}">{{ Lang::get('master.navs.mantelzorgers') }}</a>
                    </li>
                </ul>
            </li>

            @if(Session::get('hijack-original'))
                <li class="rejack">
                    <a class="btn btn-warning" href="{{ route('rejack') }}">{{ Lang::get('master.hijack.undo') }}</a>
                </li>
            @endif

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>