@extends('layout.front.master')

@section('styles')
@stop

@section('scripts')
@stop

@section('page-header')
    <h2>{{ Lang::get('front.beta.sub-header') }}</h2>
@stop

@section('content')

    <div class="beta-wrapper">

        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img class="img-responsive" src="/images/sfeer.png" alt="beta-banner"/>
                    </div>
                    <div class="col-md-6">

                        @include('form')

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop