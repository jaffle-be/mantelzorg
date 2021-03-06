@extends('layout.front.master')

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

                        <form method="post">

                            <h3><?= Lang::get('front.beta.intro-intrested') ?></h3>

                            <div class="form-group">
                                <label class="control-label" for="beta-firstname"><?= Lang::get('front.beta.firstname') ?></label>
                                <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                            </div>

                            @error('firstname')

                            <div class="form-group">
                                <label class="control-label" for="beta-lastname"><?= Lang::get('front.beta.lastname') ?></label>
                                <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                            </div>

                            @error('lastname')

                            <div class="form-group">
                                <label class="control-label" for="beta-email"><?= Lang::get('front.beta.email') ?></label>
                                <?= Form::text('email', null, array('class' => 'form-control')) ?>
                            </div>

                            @error('email')

                            <div class="form-group">
                                <label class="control-label" for="beta-organisation"><?= Lang::get('front.beta.organisation') ?></label>
                                <?= Form::text('organisation', null, array('class' => 'form-control')) ?>
                            </div>

                            @error('organisation')

                            <?php if (Session::has('message')): ?>
                            <div class="alert alert-success">
                                {{ Lang::get('front.beta.registration-thanks') }}
                            </div>
                            <?php endif; ?>

                            <div class="text-center">
                                <button class="btn btn-lg btn-primary" type="submit"><?= Lang::get('front.beta.register') ?></button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop