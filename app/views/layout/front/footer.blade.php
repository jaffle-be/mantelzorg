<div class="dot-line"></div>

<div class="footer container">



    <div class="clearfix"></div>

    <div class="pull-left">

        <ul>
            <li><a href=""><?= Lang::get('master.footer.instrument') ?></a></li>
<!--            <li><a href="">--><?//= Lang::get('master.footer.veiligheid') ?><!--</a></li>-->
<!--            <li><a href="">--><?//= Lang::get('master.footer.blog') ?><!--</a></li>-->
<!--            <li><a href="">--><?//= Lang::get('master.footer.pers') ?><!--</a></li>-->
<!--            <li><a href="">--><?//= Lang::get('master.footer.team') ?><!--</a></li>-->
<!--            <li><a href="">--><?//= Lang::get('master.footer.help') ?><!--</a></li>-->
        </ul>

        <ul>
            <li><a id="log-in" href=""><?= Lang::get('master.footer.login') ?></a></li>
            <li>
                <div class="login-form">
                    <form class="form-inline" action="">
                        <input class="form-control" type="text" name="email" id="" placeholder="<?= Lang::get('master.footer.email') ?>"/>
                        <input class="form-control" type="password" name="password" placeholder="<?= Lang::get('master.footer.password') ?>"/>
                        <input class="btn btn-info" type="submit" value="<?= Lang::get('master.footer.inloggen') ?>"/>
                    </form>
                </div>
            </li>
        </ul>

    </div>

    <div class="pull-right">
        <img class="logo-hogent" src="/img/logo-footer.png" alt=""/>
    </div>

</div>