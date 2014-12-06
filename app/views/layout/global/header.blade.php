<header>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                    <span class="sr-only">{{ Lang::get('dash/general.toggle_nav') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <h1><a class="navbar-brand" href="<?= $user ? route('dash') : route('home') ?>" id="brand">Zicht op mantelzorg</a></h1>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="nav navbar-nav">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="//zichtopmantelzorg.be">@lang('master.navs.blog')</a></li>

                    @if(!$user)
                        <li><a id="log-in" href="<?= action('IndexController@getLogin') ?>"><?= Lang::get('master.footer.login') ?></a></li>
                    @else
                        <li><a href="<?= route('dash') ?>">@lang('master.navs.instrument')</a></li>
                        <li><a id="log-out" href="<?= action('IndexController@getLogout') ?>"><?= Lang::get('master.footer.logout') ?></a></li>
                    @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>