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
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                            {!! csrf_field() !!}

                            <h3><?= Lang::get('front.reminder.title') ?></h3>

                            <div class="form-group">
                                <label class="control-label" for="email"><?= Lang::get('front.reminder.email') ?></label>

                                <?= Form::text('email', null, array('class' => 'form-control')) ?>
                            </div>

                            @include('layout.messages')

                            @if(Session::has('status'))
                                <p class="alert alert-info">{{ Session::get('status') }} </p>
                            @endif

                            <p class="text-center">
                                <button class="btn btn-lg btn-primary" type="submit"><i class="fa fa-btn fa-envelope"></i>&nbsp;<?= Lang::get('front.reminder.send') ?></button>
                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop