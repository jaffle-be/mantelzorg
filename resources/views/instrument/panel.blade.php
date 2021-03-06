@extends('layout.admin.master')

@section('scripts')

    <script src="/js/instrument.panel.min.js"></script>

@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instrument'),
            ),
            array(
                    'text' => Lang::get('master.navs.start'),
                    'href' => route('dash'),
            ),
            array(
                    'text' => Lang::get('instrument.panel'),
            ),
    )) ?>
@stop

@section('content')

    <div class="instrument panel-{{ $panel->color }}">

        <?= Form::open([
                'route' => ['instrument.panel.submit', $panel->id, $survey->id],
                'id' => 'panel-form',
        ]) ?>

        @include('instrument.template.header', ['panel' => $panel])

        <?php $first = true ?>

        <div class="instrument-questions">

            @foreach($panel->questions as $question)

                @include('instrument.template.question', ['panel' => $panel, 'question' => $question, 'survey' => $survey, 'first' => $first])

                <?php $first = false; ?>

            @endforeach

        </div>

            @include('instrument.template.footer', [ 'panel' => $panel, 'next' => $panel->nextPanel() ])

        <?php Form::close() ?>

    </div>

@stop