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

                <h1>
                    <a class="navbar-brand" href="<?= $user ? route('dash') : route('home') ?>" id="brand">
                        <img src="{{ asset('images/logo-footer.png') }}" alt="Hogent" id="logo"/>
                        <span>Zicht op mantelzorg</span>
                    </a>
                </h1>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="http://zichtopmantelzorg.be" id="main-nav-blog">@lang('master.navs.blog')</a></li>

                    @if(!$user || $forceShowLogin)
                        <li>
                            <a id="log-in" href="{{ route('login') }}">{{ Lang::get('master.footer.login') }}</a>
                        </li>
                    @else
                        <li><a href="<?= route('dash') ?>" id="main-nav-instrument">@lang('master.navs.instrument')</a></li>
                        <li>
                            <a id="log-out" href="{{ route('logout') }}">{{ Lang::get('master.footer.logout') }}</a>
                        </li>
                    @endif
                </ul>
            </div>

        </div>

        @if($includeSidebar)
            @include('layout.global.sidebar')
        @endif

    </nav>
</header>