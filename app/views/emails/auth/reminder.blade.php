@extends('layout.email.master')

@section('page-header')
    <h2>{{ Lang::get('reminders.email.title') }}</h2>
@stop

@section('content')
    <p>
        {{ Lang::get('reminders.email.body') }}
        <br/>{{ URL::route('reset', array($token)) }}
    </p>
@stop