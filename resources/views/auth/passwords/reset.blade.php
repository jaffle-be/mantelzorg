@extends('layout.front.master')

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
                    <div class="col-md-6 col-md-offset-1">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                            {!! csrf_field() !!}

                            <h3><?= Lang::get('front.reset.title') ?></h3>

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="control-label"><?= Lang::get('front.reset.email') ?></label>

                                <div>
                                    <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="control-label"><?= Lang::get('front.reset.password') ?></label>

                                <div>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="control-label"><?= Lang::get('front.reset.confirmation') ?></label>
                                <div>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            @include('layout.messages')

                            <div class="form-group">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-refresh"></i>&nbsp;<?= Lang::get('front.reset.do') ?>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop