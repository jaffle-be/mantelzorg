<section id="sidebar" class="col-md-3 col-xs-12">

    <ul>
        @if($user->admin == 1)
            <li>
                <span class="glyphicons group"></span>{{ Lang::get('master.navs.gebruikers') }}

                <ul class="subnav">
                    <li>
                        <a href="{{ route('hulpverleners.index') }}">{{ Lang::get('master.navs.hulpverleners') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('inschrijvingen.index') }}">{{ Lang::get('master.navs.inschrijvingen') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('questionnaires.index') }}"><span class="glyphicons notes_2"></span>{{ Lang::get('master.navs.questionnaires') }}
                </a>
            </li>
            <li>
                <a href="{{ route('rapport.index') }}"><span class="glyphicon glyphicon-folder-close"></span>{{ Lang::get('master.navs.rapport') }}
                </a>
            </li>
        @endif

        <li>
            <a href="{{ action('InstrumentController@index') }}"><span class="glyphicon glyphicon-list-alt"></span>{{ Lang::get('master.navs.instrument') }}
            </a>
        </li>
        <li>
            <span class="glyphicons wrench"></span>{{ Lang::get('master.navs.instellingen') }}

            <ul class="subnav">
                <li>
                    <a href="{{ action('Instelling\PersonController@index') }}">{{ Lang::get('master.navs.profiel') }}</a>
                </li>
                <li>
                    <a href="{{ route('instellingen.{hulpverlener}.mantelzorgers.index', array($user->id)) }}">{{ Lang::get('master.navs.mantelzorgers') }}</a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="clearfix"></div>

</section>