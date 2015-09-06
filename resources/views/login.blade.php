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
                    <div class="col-md-6">
                        <img class="img-responsive" src="/images/sfeerfoto.png" alt="beta-banner"/>
                    </div>
                    <div class="col-md-6">
                        <form method="post" class="form-horizontal">

                            <h3><?= Lang::get('front.login.intro') ?></h3>

                            <p>
                                <label for="email"><?= Lang::get('front.login.email') ?></label>
                                <?= Form::text('email', null, array('class' => 'form-control'))?>

                                <label for="password"><?= Lang::get('front.login.password') ?></label>
                                <input type="password" name="password" id="password" class="form-control"/>

                                @include('layout.messages')

                            <p class="text-center">
                                <input class="btn btn-lg btn-primary pull-left" type="submit" value="<?= Lang::get('front.login.sign-in') ?>"/>
                                <a class='pull-right' href="<?= URL::route('reminder') ?>"><?= Lang::get('front.login.reminder') ?></a>
                            </p>

                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop