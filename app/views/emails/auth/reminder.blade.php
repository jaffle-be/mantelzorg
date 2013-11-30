@extends('layout.email.master')

@section('content')
<h3>{{ Lang::get('reminders.email.title') }}</h3>

<p>
    {{ Lang::get('reminders.email.body') }}
    <br/>{{ URL::action('IndexController@getReset', array($token)) }}
</p>
@stop