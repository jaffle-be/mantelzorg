@extends('emails.notifications.master')


@section('content')

    <?= Lang::get('notifications.rapport.generated.body',  ['url' => route('report.show', [$report]), 'title' => $survey->title]) ?>

@stop