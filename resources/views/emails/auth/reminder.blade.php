@extends('layout.email.master')

@section('page-header')
    <h2>{{ Lang::get('reminders.email.title') }}</h2>
@stop

@section('content')

    <div class="container">
        <p>
            {{ Lang::get('reminders.email.body') }}
            <br/><a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
        </p>
    </div>
@stop