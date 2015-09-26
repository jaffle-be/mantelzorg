@extends('layout.admin.master')

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="/js/stats.insights.question.min.js"></script>
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

    @include('stats.tabs', ['active' => 'insights.question'])

    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">{{ Lang::get('stats.titles.question') }}</h3>
                </div>

                <div class="panel-body" id="question-panel">

                    <div class="row">
                        <div class="col-sm-6">

                            <div class="form-group">

                                <label for="panel" class="control-label"></label>
                                <select name="panel" id="panel" class="form-control">
                                    @foreach($survey->panels as $panel)
                                        <option value="{{ $panel->id }}">{{ $panel->title }}</option>
                                    @endforeach
                                </select>

                            </div>

                        </div>

                        <div class="col-sm-6">

                            <div class="form-group">

                                <label for="question" class="control-label"></label>
                                <select name="question" id="question" class="form-control">
                                    @foreach($survey->panels->first()->questions as $question)
                                        <option value="{{ $question->id }}">{{ $question->displayName }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <div id="question-multiple-choise" class="col-xs-12 col-lg-6"></div>

                    <div class="col-xs-12 col-lg-6" style="padding-top: 20px;">
                        <h3 id="question-title"></h3>

                        <p id="question-info"></p>

                        <div id="question-meta" class="alert alert-info alert-sm"></div>
                        <p id="question-options"></p>
                    </div>

                    <div class="col-xs-12">
                        <div id="question-regular">
                            <p>{{ Lang::get('stats.term-click-hint') }}</p>
                        </div>
                    </div>

                    <div id="question-term" class="col-xs-12"></div>
                </div>

            </div>

        </div>

    </div>

@stop