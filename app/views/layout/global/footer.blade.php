<footer>

    <section class="container clearfix" id="footnav">

        <nav>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <h3>@lang('master.navs.blog')</h3>

                    <p>
                        @lang('master.navs.blog-text', array('url' => 'http://zichtopmantelzorg.be'))
                    </p>
                </div>
                <div class="col-xs-12 col-md-4">
                    <h3>@lang('master.navs.instrument')</h3>

                    <p>
                        @lang('master.navs.instrument-text', array('url' => $user ? route('dash') : route('home')))
                    </p>
                </div>
                <div class="col-xs-12 col-md-4">
                    <h3>@lang('master.navs.contact')</h3>

                    <p>
                        @lang('master.navs.contact-text', array('url' => 'http://zichtopmantelzorg.be/contact'))
                    </p>
                </div>
            </div>
        </nav>

    </section>

    <section id="copyright">

        <div class="container">

            <div class="row">

                <p class="col-xs-6">
                    <? $date = new DateTime() ?>
                    @if($date->format('Y') === '2013')
                        {{ 'Copyright &copy; 2013'}}
                    @else
                        {{'Copyright &copy; 2013 - ' . $date->format('Y')}}
                    @endif
                </p>

                <p class="col-xs-6">
                    <a target="_blank" href="http://www.hogent.be">
                        <img class="logo-hogent" src="{{ asset('/images/logo-footer.png') }}" alt=""/>
                    </a>
                </p>

            </div>

        </div>

    </section>

    </div>

</footer>