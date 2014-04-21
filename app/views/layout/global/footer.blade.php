<div class="dot-line"></div>

<div class="footer container">



    <div class="clearfix"></div>

    <div class="pull-left">

        <ul>
            <li><a href="<?= URL::action('IndexController@getHetInstrument') ?>"><?= Lang::get('master.footer.instrument') ?></a></li>
            <!--            <li><a href="">--><?//= Lang::get('master.footer.veiligheid') ?><!--</a></li>-->
            <!--            <li><a href="">--><?//= Lang::get('master.footer.blog') ?><!--</a></li>-->
            <!--            <li><a href="">--><?//= Lang::get('master.footer.pers') ?><!--</a></li>-->
            <li><a href="<?= URL::action('IndexController@getHetTeam') ?>"><?= Lang::get('master.footer.team') ?></a></li>
            <li>
                <a target="_blank" href="http://www.youtube.com/watch?v=gqDFSs_z5_A&feature=youtu.be">Film studiedag</a>
            </li>
            <!--            <li><a href="">--><?//= Lang::get('master.footer.help') ?><!--</a></li>-->
        </ul>


        <ul>
            @if(!$user)
            <li><a id="log-in" href="<?= URL::action('IndexController@getLogin') ?>"><?= Lang::get('master.footer.login') ?></a></li>
            @else
            <li><a id="log-out" href="<?= URL::action('IndexController@getLogout') ?>"><?= Lang::get('master.footer.logout') ?></a></li>
            @endif

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