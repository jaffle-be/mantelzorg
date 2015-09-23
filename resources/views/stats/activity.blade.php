@extends('layout.admin.master')

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="/js/stats.activity.min.js"></script>
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@stop

@section('page-header')
    <?= Template::crumb(array(
            array('text' => Lang::get('master.navs.stats')),
            array('text' => Lang::get('master.navs.stats'))
    )) ?>
@stop

@section('content')

    @include('stats.tabs', ['active' => 'activity'])

    <div class="row">

        <div class="col-xs-12 col-lg-6">
            @include('stats.sessions')
        </div>

        <div class="col-xs-12 col-lg-6">
            @include('stats.organisations')
        </div>

    </div>

@stop