<!DOCTYPE html>
<html class="pdf">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('/css/master.min.css') }}"/>
    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet' type='text/css'>

</head>
<body>

<div class="container">

    @include('instrument.pdf.personals', ['session' => $session])

    @foreach($session->questionnaire->panels as $panel)

        @include('instrument.pdf.panel')

    @endforeach

</div>

</body>
</html>
