@extends('layout.admin.master')

@section('styles')
    <link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.gebruikers'),
            ),

            array(
                    'text' => Lang::get('master.navs.hulpverleners'),

                    'href' => route('hulpverleners.index'),
            ),

            array(
                    'text' => Lang::get('master.navs.wijzig'),
            ),
    )) ?>
@stop

@section('content')

    @include('hulpverlener.form')

    @include('modals.organisation-creator')

    @include('modals.location-creator')

@stop