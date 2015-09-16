@extends('layout.admin.master')

@section('styles')
    <link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.instellingen'),
                    'href' => route('instellingen.index')
    ),
    array(
    'text' => Lang::get('master.navs.mantelzorgers'),
    'href' => route('instellingen.{hulpverlener}.mantelzorgers.index', array(Auth::user()->id))
    ),
    array(
    'text' => Lang::get('master.navs.nieuw')
    )

    )) ?>
@stop

@section('content')

    <?= Form::open(array('route' => array('instellingen.{hulpverlener}.mantelzorgers.store', $hulpverlener->id), 'method' => 'post')) ?>

    <div class="row">

        <div class="col-md-12">

            <fieldset>

                <legend><?= Lang::get('users.persoonlijk') ?></legend>

                <div class="row">

                    <div class="col-md-6">
                        <label for="identifier"><?= Lang::get('users.identifier') ?></label>
                        @if($errors->has('identifier'))
                        <span class="error-identifier errors">{{ $errors->first('identifier') }}</span>
                        @endif

                        <div class="input-group">
                            <?= Form::text('identifier', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                        </div>

                        <label for="firstname"><?= Lang::get('users.firstname') ?></label>

                        @if($errors->has('firstname'))
                            <span class="error-firstname errors">{{ $errors->first('firstname') }}</span>
                        @endif

                        <div class="input-group">
                            <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>

                        <label for="lastname"><?= Lang::get('users.lastname') ?></label>
                        @if($errors->has('lastname'))
                            <span class="error-lastname errors">{{ $errors->first('lastname') }}</span>
                        @endif

                        <div class="input-group">
                            <?= Form::text('lastname', null , array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>


                        <div class="row">

                            <div class="col-md-6">
                                <label for="birthday"><?= Lang::get('users.birthday') ?></label>

                                @if($errors->has('birthday'))
                                    <span class="block error-birthday errors">{{ $errors->first('birthday') }}</span>
                                @endif

                                <div class="input-group">
                                    <?= Form::text('birthday', null, array('class' => 'form-control datepicker')) ?>
                                    <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                                </div>
                            </div>

                            <div class="col-md-6 gender">

                                @if($errors->has('male'))
                                    <span class="block error-male errors">{{ $errors->first('male') }}</span>
                                @endif

                                <div>
                                    <div class="radio-inline">
                                        <label>
                                            <?= Form::radio('male', 1) ?><?= Lang::get('users.male') ?>&nbsp;<i class="fa fa-male"></i>
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <?= Form::radio('male', 0) ?><?= Lang::get('users.female') ?>&nbsp;<i class="fa fa-female"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <label for="street"><?= Lang::get('users.street') ?></label>
                        @if($errors->has('street'))
                            <span class="error-street errors">{{ $errors->first('street') }}</span>
                        @endif

                        <div class="input-group">
                            <?= Form::text('street', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-road"></i></span>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="postal"><?= Lang::get('users.postal') ?></label>

                                <div class="input-group">
                                    <?= Form::text('postal', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon"><i class="fa fa-road"></i></span>
                                </div>
                                @if($errors->has('postal'))
                                    <span class="block error-postal errors">{{ $errors->first('postal') }}</span>
                                @endif
                            </div>

                            <div class="col-md-8">
                                <label for="city"><?= Lang::get('users.city') ?></label>

                                <div class="input-group">
                                    <?= Form::text('city', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon"><i class="fa fa-road"></i></span>
                                </div>
                                @if($errors->has('city'))
                                    <span class="block error-city errors">{{ $errors->first('city') }}</span>
                                @endif
                            </div>
                        </div>

                        <label for="email"><?= Lang::get('users.email') ?></label>
                        @if($errors->has('email'))
                            <span class="error-email errors">{{ $errors->first('email') }}</span>
                        @endif

                        <div class="input-group">
                            <?= Form::text('email', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon">@</span>
                        </div>

                        <label for="phone"><?= Lang::get('users.phone') ?></label>
                        @if($errors->has('phone'))
                            <span class="error-phone errors">{{ $errors->first('phone') }}</span>
                        @endif

                        <div class="input-group">
                            <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        </div>


                    </div>

                </div>

            </fieldset>

        </div>

    </div>

    <div>
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('users.create_mantelzorger') ?>"/>
    </div>


    <?= Form::close() ?>


@stop