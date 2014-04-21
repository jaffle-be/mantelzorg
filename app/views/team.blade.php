@section('styles')
<link rel="stylesheet" href="/css/instrument.min.css"/>
@stop

@section('content')

<div class="container-small">

    <div>
        <h3><?= Lang::get('front.team.title') ?></h3>

        <address>
            <strong>Lieve De Vos</strong><br>
            ergotherapeut & orthopedagoog - lector Opleiding Ergotherapie HoGent <a target="_blank" href="http://pure.hogent.be/portal/en/persons/lieve-de-vos%28ca8ee9bb-f5e2-405c-a16e-915d5b2896a3%29.html">(Expertise)</a><br>
            <a href="mailto:lieve.devos@hogent.be">lieve.devos@hogent.be</a><br>
        </address>

        <address>
            <strong>Benedicte De Koker</strong><br>
            socioloog - onderzoeker HoGent <a target="_blank" href="http://pure.hogent.be/portal/en/persons/benedicte-de-koker%284d92f7a5-e062-4d72-a9bd-6dcd0134ee74%29/publications.html">(Expertise)</a><br>
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