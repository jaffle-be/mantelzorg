@extends('layout.admin.master')

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instrument'),
            ),
            array(
                    'text' => Lang::get('master.navs.start'),
                    'href' => route('dash')
            ),
            array(
                    'text' => Lang::get('instrument.view')
            )
    )) ?>
@stop

@section('content')

    @include('instrument.pdf.template')

@stop