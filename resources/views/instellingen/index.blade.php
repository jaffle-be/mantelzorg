@extends('layout.admin.master')

@section('styles')
    <link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('page-header')
    <?= Template::crumb(array(

            array(
                    'text' => Lang::get('master.navs.instellingen'),
            ),

            array('text' => Lang::get('master.navs.profiel'))

    )) ?>

@stop

@section('content')

    @include('instellingen.form')

@stop