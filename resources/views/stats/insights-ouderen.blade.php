@extends('layout.admin.master')

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="/js/stats.insights.ouderen.min.js"></script>
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@stop

@section('page-header')
    <?= Template::crumb(array(
            array('text' => Lang::get('master.navs.stats')),
            array('text' => Lang::get('master.navs.stats')),
    )) ?>
@stop

@section('content')

    @include('stats.tabs', ['active' => 'insights.ouderen'])

    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">{{ Lang::get('stats.titles.ouderen') }}</h3>
                </div>

                <div class="panel-body">
                    <div class="chart">
                        <h4>{{ Lang::get('stats.titles.relation-mantelzorger') }}</h4>
                        <div id="mantelzorger_relation"></div>
                    </div>

                    <div class="chart">
                        <h4>{{ Lang::get('stats.titles.woonsituatie') }}</h4>
                        <div id="ouderen_woonsituatie"></div>
                    </div>

                    <div class="chart">
                        <h4>{{ Lang::get('stats.titles.oorzaak-hulpbehoefte') }}</h4>
                        <div id="oorzaak_hulpbehoefte"></div>
                    </div>

                    <div class="chart">
                        <h4>{{ Lang::get('stats.titles.belprofiel') }}</h4>
                        <div id="bel_profiel"></div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@stop