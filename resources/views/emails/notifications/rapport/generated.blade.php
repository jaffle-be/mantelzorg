@extends('emails.notifications.master')


@section('content')

    <div class="container">

        <?= Lang::get('notifications.rapport.generated.body',  ['url' => route('report.show', [$report]), 'title' => $survey->title]) ?>

    </div>

@stop