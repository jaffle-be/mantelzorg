<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse" id="sidebar">
        <ul class="nav" id="side-menu">

            @if($user->admin == 1)
                <li>
                    <a class="btn btn-default" href="#" id="nav-users-dropdown"><span class="fa fa-users"></span>&nbsp;{{ Lang::get('master.navs.gebruikers') }}
                        <span class="fa arrow"></span></a>

                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a class="btn btn-default" href="{{ route('hulpverleners.index') }}" id="nav-hulpverleners">{{ Lang::get('master.navs.hulpverleners') }}</a>
                        </li>
                        <li>
                            <a class="btn btn-default" href="{{ route('inschrijvingen.index') }}" id="nav-inschrijvingen">{{ Lang::get('master.navs.inschrijvingen') }}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="btn btn-default" href="{{ route('survey.index') }}" id="nav-surveys"><span class="fa fa-file-text-o"></span>&nbsp;{{ Lang::get('master.navs.questionnaires') }}
                    </a>
                </li>
                <li>
                    <a class="btn btn-default" href="{{ route('rapport.index') }}" id="nav-rapport"><span class="fa fa-folder-open"></span>&nbsp;{{ Lang::get('master.navs.rapport') }}</a>
                </li>

                @if($user->id == 1)
                    <li>
                        <a class="btn btn-default" href="{{ route('stats.index') }}" id="nav-stats"><i class="fa fa-line-chart"></i>&nbsp;{{ Lang::get('master.navs.stats') }}</a>
                    </li>
                @endif
            @endif

            <li>
                <a class="btn btn-default" href="{{ route('dash') }}" id="nav-instrument"><span class="fa fa-list-alt"></span>&nbsp;{{ Lang::get('master.navs.instrument') }}
                </a>
            </li>
            <li>
                <a class="btn btn-default" href="#"><span class="fa fa-wrench"></span>&nbsp;{{ Lang::get('master.navs.instellingen') }}<span class="fa arrow"></span></a>

                <ul class="nav nav-second-level  collapse">
                    <li>
                        <a class="btn btn-default" href="{{ route('instellingen.index') }}" id="nav-profiel">{{ Lang::get('master.navs.profiel') }}</a>
                    </li>
                    <li>
                        <a class="btn btn-default" href="{{ route('instellingen.{hulpverlener}.mantelzorgers.index', array($user->id)) }}" id="nav-mantelzorgers">{{ Lang::get('master.navs.mantelzorgers') }}</a>
                    </li>
                </ul>
            </li>

            @if(Session::get('hijack-original'))
                <li class="rejack">
                    <a class="btn btn-default" class="btn btn-warning" href="{{ route('rejack') }}">{{ Lang::get('master.hijack.undo') }}</a>
                </li>
            @endif

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>