<nav>
    <ul class="nav nav-pills">
        <li class="<?= $page == 'hulpverlener' ? 'active' : '' ?>"><a href="<?= URL::action('HulpverlenerController@index') ?>"><?= Lang::get('master.navs.hulpverleners') ?></a></li>
        <li class="<?= $page == 'inschrijving' ? 'active' : '' ?>"><a href="<?= URL::action('InschrijvingController@index') ?>"><?= Lang::get('master.navs.inschrijvingen') ?></a></li>
    </ul>
</nav>