@extends('layout.email.master')

@section('page-header')
    <h2>{{ Lang::get('reminders.email.title') }}</h2>
@stop

@section('content')

    <div class="container">
        <p>
            {{ Lang::get('reminders.email.body') }}
            <br/>{{ route('reset', array($token)) }}
        </p>
    </div>
@stop