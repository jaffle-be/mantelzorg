<nav>
    <ul class="nav nav-pills">
        <li class="<?= $page == 'personal' ? 'active' : '' ?>"><a href="<?= URL::action('Instelling\PersonController@index') ?>"><?= Lang::get('master.navs.personal') ?></a></li>
        <li class="<?= $page == 'mantelzorger' ? 'active' : '' ?>"><a href="<?= URL::route('instellingen.{hulpverlener}.mantelzorgers.index', array(Auth::user()->id)) ?>"><?= Lang::get('master.navs.mantelzorgers') ?></a></li>
    </ul>
</nav>