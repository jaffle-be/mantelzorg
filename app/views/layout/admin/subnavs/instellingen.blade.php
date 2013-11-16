<nav>
    <ul class="nav nav-pills">
        <li class="<?= $page == 'personal' ? 'active' : '' ?>"><a href="<?= URL::action('Instelling\PersonController@getIndex') ?>"><?= Lang::get('master.navs.personal') ?></a></li>
        <li class="<?= $page == 'mantelzorger' ? 'active' : '' ?>"><a href="<?= URL::action('Instelling\MantelzorgerController@index') ?>"><?= Lang::get('master.navs.mantelzorgers') ?></a></li>
    </ul>
</nav>