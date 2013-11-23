@extends('layout.email.master')

@section('content')

<h2><?= Lang::get('email.registration.title') ?></h2>

<p>
    <?= Lang::get('email.registration.foreword') ?>
</p>

<p>
    <?= Lang::get('email.registration.information', array('password' => $password)) ?>
</p>

@stop