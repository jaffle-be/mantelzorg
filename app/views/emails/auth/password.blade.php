@extends('layout.email.master')

@section('content')

<h2><?= Lang::get('email.registration.title') ?></h2>

<p>
    <?= Lang::get('email.registration.hello', array('firstname' => $user->firstname)) ?>
    <br/><br/>
    <?= Lang::get('email.registration.foreword') ?>
</p>

<p>
    <?= Lang::get('email.registration.information', array('password' => $original)) ?>
</p>

<p>
    <?= Lang::get('email.registration.closing') ?>
</p>

@stop