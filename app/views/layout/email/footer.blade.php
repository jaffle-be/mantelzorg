<div class="dot-line"></div>

<div class="footer container">

    <div class="clearfix"></div>

    <div class="pull-left">

        <ul>
            <li><a href="<?= URL::action('IndexController@getHetInstrument') ?>"><?= Lang::get('master.footer.instrument') ?></a></li>
            <li><a href="<?= URL::action('IndexController@getHetTeam') ?>"><?= Lang::get('master.footer.team') ?></a></li>
        </ul>

    </div>

    <div class="pull-right">
        <span class="copyright">
        <?
        $date = new DateTime();
        if($date->format('Y') === '2013'){
            echo 'Copyright ® 2013';
        }
        else{
            echo 'Copyright ® 2013 - ' . $date->format('Y');
        }
        ?>
        </span>
        <a target="_blank" href="http://www.hogent.be">
            <img class="logo-hogent" src="/images/logo-footer.png" alt=""/>
        </a>
    </div>

</div>