@extends('layout.front.master')

@section('styles')

@stop

@section('scripts')
@stop

@section('page-header')
    <h2><?= Lang::get('front.beta.sub-header') ?></h2>
@stop

@section('content')

    <div class="beta-wrapper">

        <div class="banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <img class="img-responsive" src="/images/sfeerfoto.png" alt="beta-banner"/>
                    </div>
                    <div class="col-md-offset-1 col-md-6">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {!! csrf_field() !!}

                            <h3><?= Lang::get('front.login.intro') ?></h3>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="control-label"><?= Lang::get('front.login.email') ?></label>

                                <div>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="control-label"><?= Lang::get('front.login.password') ?></label>

                                <div>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                                @include('layout.messages')

                            <p class="text-center">
                                <button class="btn btn-lg btn-primary pull-left" type="submit" id="sign-in"><?= Lang::get('front.login.sign-in') ?></button>
                                <a class='pull-right btn btn-sm btn-link' href="{{ url('/password/reset') }}"><?= Lang::get('front.login.reminder') ?></a>
                            </p>

                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop