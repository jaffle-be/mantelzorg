@section('styles')
<link rel="stylesheet" href="/css/instrument.min.css"/>
@stop

@section('title', Lang::get('instrument.main-header'))

@section('content')

<div class="container">

    <div>
        <h3><?= Lang::get('instrument.s1-title') ?></h3>

        <p><?= Lang::get('instrument.s1-p1') ?></p>
        <p><?= Lang::get('instrument.s1-p2') ?></p>
        <p><?= Lang::get('instrument.s1-p3') ?></p>
    </div>

    <div>
        <h3><?= Lang::get('instrument.s2-title') ?></h3>

        <p><?= Lang::get('instrument.s2-p1') ?></p>
        <p><?= Lang::get('instrument.s2-p2') ?></p>
        <p><?= Lang::get('instrument.s2-p3') ?></p>

    </div>

    <div>
        <h3><?= Lang::get('instrument.s3-title') ?></h3>

        <p><?= Lang::get('instrument.s3-p1') ?></p>
        <p><?= Lang::get('instrument.s3-p2') ?></p>
        <p><?= Lang::get('instrument.s3-p3') ?></p>

    </div>

</div>

@stop