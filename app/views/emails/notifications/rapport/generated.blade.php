@extends('emails.notifications.master')


@section('content')

    <?= Lang::get('notifications.rapport.generated.body',  ['url' => route('rapport.download', [$filename]), 'title' => $survey->title]) ?>

@stop

