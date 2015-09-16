@extends('layout.admin.master')

@section('styles')
    <link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array(
                    'text' => Lang::get('master.navs.gebruikers'),
            ),

            array(
                    'text' => Lang::get('master.navs.inschrijvingen'),

                    'href' => URL::route('inschrijvingen.index'),
            ),

            array(
                    'text' => Lang::get('master.navs.wijzig')
            )
    )) ?>
@stop

@section('content')

    <?= Form::open(['route' => ['inschrijvingen.update', $inschrijving->id], 'method' => 'put', 'id' => 'create-user-form']) ?>

    <div class="row">

        <div class="col-md-6">
            <input name="id" type="hidden" value="<?= $inschrijving->id ?>"/>

            <label for="email"><?= Lang::get('users.email') ?></label>

            @if($errors->has('email'))
                <span class="errors error-email"><?= $errors->first('email') ?></span>
            @endif

            <div class="input-group">
                <?= Form::text('email', $inschrijving->email, array('class' => 'form-control')) ?>
                <span class="input-group-addon">@</span>
            </div>

            <label for="firstname"><?= Lang::get('users.firstname') ?></label>

            @if($errors->has('firstname'))
                <span class="errors  error-firstname"><?= $errors->first('firstname') ?></span>
            @endif

            <div class="input-group">
                <?= Form::text('firstname', $inschrijving->firstname, array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
            </div>

            <label for="lastname"><?= Lang::get('users.lastname') ?></label>

            @if($errors->has('lastname'))
                <span class="errors  error-lastname"><?= $errors->first('lastname') ?></span>
            @endif

            <div class="input-group">
                <?= Form::text('lastname', $inschrijving->lastname, array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
            </div>


            @if($errors->has('male'))
                <span class="errors error-male"><?= $errors->first('male') ?></span>
            @endif

            <div>
                <div class="radio-inline">
                    <label>
                        <?= Form::radio('male', 1, ['id' => 'male']) ?><?= Lang::get('users.male') ?>&nbsp;<i class="fa fa-male"></i>
                    </label>
                </div>
                <div class="radio-inline">
                    <label>
                        <?= Form::radio('male', 0, ['id' => 'female']) ?><?= Lang::get('users.female') ?>&nbsp;<i class="fa fa-female"></i>
                    </label>
                </div>
            </div>

            <label for="phone"><?= Lang::get('users.phone') ?></label>

            @if($errors->has('phone'))
                <span class="errors error-phone"><?= $errors->first('phone') ?></span>
            @endif

            <div class="input-group">
                <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
            </div>

        </div>

        <div class="col-md-6">

            <div class="input-select">
                <label for="organisation">
                    <?= Lang::get('users.organisatie') ?>
                </label>

                @if($errors->has('organisation_id'))
                    <span class="errors error-organisation"><?= $errors->first('organisation_id') ?></span>
                @endif

                <?= Form::select('organisation_id', $organisations, null, array(
                                'id'    => 'organisation',
                                'class' => 'form-control')
                )?>
            </div>

            <p class="alert alert-info"><?= Lang::get('users.oorspronkelijke_organisatie', array('organisatie' => $inschrijving->organisation)) ?></p>

            <div class="input-select holder <?= !Input::old('organisation_id') ? 'hide' : '' ?>">
                <label for="locations"><?= Lang::get('users.locations') ?></label>

                @if($errors->has('organisation_location_id'))
                    <span class="errors error-organisation-location"><?= $errors->first('organisation_location_id') ?></span>
                @endif
                <?= Form::select('organisation_location_id', $locations, Input::old('organisation_location_id'), array(
                        'id'    => 'location',
                        'class' => 'form-control',
                )) ?>
            </div>

        </div>

    </div>

    <div class="form-actions">
        <button id="create-user" class="btn btn-primary" type="submit"><?= Lang::get('users.create_as_helper') ?></button>
    </div>

    </form>


    <?= $creatorOrganisations ?>

    <?= $creatorLocations ?>

@stop