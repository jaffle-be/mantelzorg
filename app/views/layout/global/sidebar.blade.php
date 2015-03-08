<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse" id="sidebar">
        <ul class="nav" id="side-menu">
            {{--<li class="sidebar-search">--}}
                {{--<div class="input-group custom-search-form">--}}
                    {{--<input type="text" class="form-control" placeholder="Search...">--}}
                                {{--<span class="input-group-btn">--}}
                                {{--<button class="btn btn-default" type="button">--}}
                                    {{--<i class="fa fa-search"></i>--}}
                                {{--</button>--}}
                            {{--</span>--}}
                {{--</div>--}}
                {{--<!-- /input-group -->--}}
            {{--</li>--}}

            @if($user->admin == 1)
                <li>

                    <a href="#"><span class="fa fa-users"></span>&nbsp;{{ Lang::get('master.navs.gebruikers') }}
                        <span class="fa arrow"></span></a>

                    <ul class="nav nav-second-level">
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
                    <a href="{{ route('rapport.index') }}"><span class="fa fa-folder-open"></span>&nbsp;{{ Lang::get('master.navs.rapport') }}
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ action('InstrumentController@index') }}"><span class="fa fa-list-alt"></span>&nbsp;{{ Lang::get('master.navs.instrument') }}
                </a>
            </li>
            <li>
                <a href="#"><span class="fa fa-wrench"></span>&nbsp;{{ Lang::get('master.navs.instellingen') }}<span class="fa arrow"></span></a>

                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ action('Instelling\PersonController@index') }}">{{ Lang::get('master.navs.profiel') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('instellingen.{hulpverlener}.mantelzorgers.index', array($user->id)) }}">{{ Lang::get('master.navs.mantelzorgers') }}</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>