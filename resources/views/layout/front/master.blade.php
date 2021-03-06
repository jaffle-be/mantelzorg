<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= ucfirst(strtolower(Lang::get('master.main-title'))) ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/master.min.css"/>
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    @yield('styles')

    <script src="/js/modernizr.min.js"></script>

    @if(app()->environment() != 'testing')
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
    @endif

</head>
<body>

@include('layout.global.header', ['includeSidebar' => false, 'forceShowLogin' => false])

<section id="page-header">
    <div class="container">
        @yield('page-header')
    </div>
</section>

<section id="content">

    @yield('content')

</section>

@include('layout.global.footer')

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/material.min.js"></script>
<script type="text/javascript" src="/js/ripples.min.js"></script>
<script type="text/javascript" src="/js/master.min.js"></script>

@yield('scripts')

@if(app()->environment() != 'testing')
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
@endif

</body>
</html>
