<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= ucfirst(strtolower(Lang::get('master.main-title'))) ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/master.min.css"/>
    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet' type='text/css'>

    @yield('styles')

    <script src="/js/modernizr.min.js"></script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-44264141-1']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>

    @if(Agent::isTablet() || Agnet::isMobile())
        <style rel="stylesheet">html {
                background-color: white;
            }</style>
    @endif


</head>
<body class="{{ Agent::isTablet() || Agent::isMobile() ? 'tablet' : ''}}">

<div id="wrapper">

    @include('layout.global.header', ['includeSidebar' => true])

    <div id="page-wrapper">

        {{--added this so we can clear it with an @overwrite on the instrument page --}}
        @section('page-header-wrapper')
            <section id="page-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
                    <span class="sr-only">{{ Lang::get('dash/general.toggle_nav') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="left">
                    @yield('page-header')
                </div>
                <div class="right">
                    @include('layout.messages')
                </div>
                <div class="clearfix"></div>
            </section>
        @stop

        @yield('page-header-wrapper')

        <section id="content">
            <div class="container-fluid">
                @yield('content')
            </div>

        </section>

        @include('modals.confirmation')

    </div>

    @unless($fullScreen)
        @include('layout.global.footer')
    @endunless

    <div class="sidebar-collapser">
        <a href="#">
            <i class="fa fa-2x fa-arrow-left"></i>
            <i class="fa fa-2x fa-arrow-right" style="display: none;"></i>
        </a>
    </div>

</div>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/master.min.js"></script>

@yield('scripts')

<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-44264141-1', 'zichtopmantelzorg.be');
    ga('send', 'pageview');

</script>

{{--make sure to clear all floats--}}
<div class="clearfix"></div>
</body>
</html>
