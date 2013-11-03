
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= ucfirst(strtolower(Lang::get('master.main-title'))) ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <link rel="stylesheet" href="/css/bootstrap.css"/>
    <link rel="stylesheet" href="/css/bootstrap-theme.css"/>
    <link rel="stylesheet" href="/css/glyphicons.css"/>
    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="/css/master.min.css"/>

    @yield('styles')

    <script src="/vendor/modernizr-2.6.2.min.js"></script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-44264141-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>

</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

@include('layout.admin.header')

<div class="content-wrapper">

    <div class="container">

        <section id="sidebar">
            <ul>
                <li>
                    <a href="<?= URL::action('DashController@getIndex') ?>">
                        <span class="glyphicon glyphicon-dashboard"></span><?= Lang::get('sidebar.dashboard') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= URL::action('InstrumentController@getIndex') ?>">
                        <span class="glyphicon glyphicon-list-alt"></span><?= Lang::get('sidebar.instrument') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= URL::action('RapportController@getIndex') ?>">
                        <span class="glyphicon glyphicon-folder-close"></span><?= Lang::get('sidebar.rapport') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= URL::action('InstellingenController@getIndex') ?>">
                        <span class="glyphicon glyphicon-wrench"></span><?= Lang::get('sidebar.instellingen') ?></a>
                </li>
            </ul>
        </section>

        <section id="content" class="container">
            @yield('content')
        </section>

    </div>


</div>

@include('layout.global.footer')



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/vendor/jquery-1.10.2.min.js"><\/script>')</script>
<script type="text/javascript" src="/vendor/bootstrap.min.js"></script>

<!--<script type="text/javascript" src="/js/master.min.js"></script>-->

@yield('scripts')

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-44264141-1', 'zichtopmantelzorg.be');
    ga('send', 'pageview');

</script>

</body>
</html>
