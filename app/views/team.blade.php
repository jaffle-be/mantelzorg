@section('styles')
<link rel="stylesheet" href="/css/instrument.min.css"/>
@stop

@section('content')

<div class="container-small">

    <div>
        <h3><?= Lang::get('front.team.title') ?></h3>

        <address>
            <strong>Lieve De Vos</strong><br>
            ergotherapeut & orthopedagoog - lector Opleiding Ergotherapie HoGent<br>
            <a href="mailto:lieve.devos@hogent.be">lieve.devos@hogent.be</a><br>
        </address>

        <address>
            <strong>Benedicte De Koker</strong><br>
            socioloog - onderzoeker HoGent<br>
            <a href="mailto:benedicte.dekoker@hogent.be">benedicte.dekoker@hogent.be</a><br>
        </address>

        <address>
            <strong>Faculteit Mens & Welzijn</strong><br>
            Keramiekstraat 80<br>
            9000 Gent<br>
            <abbr title="<?= Lang::get('front.team.telefoon') ?>"><?= Lang::get('front.team.telefoon_abbr') ?>:</abbr> 09/ 321 21 38
        </address>
    </div>

</div>

@stop