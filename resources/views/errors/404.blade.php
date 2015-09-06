@extends('layout.front.master')

@section('styles')
@stop

@section('scripts')
@stop

@section('page-header')
    <h2>{{ Lang::get('errors.404.title') }}</h2>
@stop

@section('content')

    <div class="container">
        <em>{{ Lang::get('errors.404.content') }}</em>
    </div>

@stop