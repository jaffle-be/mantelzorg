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

        <div class="col-xs-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">{{ Lang::get('stats.titles.sessions') }}</h3>
                </div>

                <div class="panel-body">

                    <div id="sessions"></div>

                </div>

            </div>

        </div>

        <div class="col-xs-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">{{ Lang::get('stats.titles.organisations') }}</h3>
                </div>

                <div class="panel-body">

                    <div id="organisations"></div>

                </div>

            </div>

        </div>

    </div>

@stop