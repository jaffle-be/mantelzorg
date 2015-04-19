<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= ucfirst(strtolower(Lang::get('master.main-title'))) ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?= asset('/css/master.min.css') ?>"/>

    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet' type='text/css'>

    @yield('styles')

</head>
<body>

@include('layout.global.header', ['includeSidebar' => false, 'forceShowLogin' => true ])

<section id="page-header">
    <div class="container">
        @yield('page-header')
    </div>
</section>

<section id="content">

    <div class="container">
        <section id="main-content">
            @yield('content')
        </section>
    </div>

</section>

@include('layout.global.footer')

</body>
</html>
