@extends('layout.admin.master')

@section('styles')
    <link rel="stylesheet" href="<?= asset('css/users.min.css') ?>"/>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instellingen'),
                    'href' => route('instellingen.index'),
    ),
    array(
    'text' => Lang::get('master.navs.mantelzorgers'),
    'href' => route('instellingen.{hulpverlener}.mantelzorgers.index', array(Auth::user()->id)),
    ),
    array(
    'text' => Lang::get('master.navs.ouderen'),
    ),
    array(
    'text' => Lang::get('master.navs.wijzig'),
    ),

    )) ?>
@stop

@section('content')

    @include('instellingen.ouderen.form', ['mode' => 'edit'])


@stop