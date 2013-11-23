@section('content')

<? var_dump($password) ?>

<h2><?= Lang::get('email.registration.title') ?></h2>

<p>
    <?= Lang::get('email.registration.foreword') ?>
</p>

<p>
    <?= Lang::get('email.registration.information', array('password' => 'test')) ?>
</p>

@stop