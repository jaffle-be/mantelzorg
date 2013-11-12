@section('styles')
<link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('content')

<?= $subnav ?>

<?= Form::model($user, array('action' => array('HulpverlenerController@update', $user->id), 'method' => 'put')) ?>
<div class="row">
    <div class="col-md-6">


            <fieldset>
                <legend><?= Lang::get('users.persoonlijk') ?></legend>

                <label for="firstname"><?= Lang::get('users.firstname') ?></label>
                <span class="errors"><?= $errors->first('firstname') ?></span>
                <div class="input-group">
                    <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                </div>

                <label for="lastname"><?= Lang::get('users.lastname') ?></label>
                <span class="errors"><?= $errors->first('lastname') ?></span>
                <div class="input-group">
                    <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                </div>


                <span class="errors"><?= $errors->first('male') ?></span>
                <div class="input-group">
                    <div class="radio-inline">
                        <label>
                            <?= Form::radio('male', 1) ?><?= Lang::get('users.male') ?>&nbsp;<i class="glyphicons male"></i>
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                            <?= Form::radio('male', 0) ?><?= Lang::get('users.female') ?>&nbsp;<i class="glyphicons female"></i>
                        </label>
                    </div>
                </div>

                <label for="phone"><?= Lang::get('users.phone') ?></label>
                <span class="errors"><?= $errors->first('phone') ?></span>
                <div class="input-group">
                    <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                </div>

            </fieldset>

    </div>

    <div class="col-md-6">
        <fieldset>
            <legend><?= Lang::get('users.organisatie') ?></legend>

            <label for="organisation">
                <?= Lang::get('users.organisatie') ?>
            </label>
            <span class="errors"><?= $errors->first('organisation_id') ?></span>

            <?= Form::select('organisation_id', $organisations, null, array(
                    'id' => 'organisation',
                    'class' => 'form-control')
            )?>


            <label for="locations"><?= Lang::get('users.locations') ?></label>
            <span class="errors"><?= $errors->first('organisation_location_id') ?></span>
            <?= Form::select('organisation_location_id', $locations, null, array(
                'id' => 'location',
                'class' => 'form-control',
            )) ?>

        </fieldset>
    </div>

</div>

<input class="btn btn-primary" type="submit" value="<?= Lang::get('users.save') ?>"/>

</form>

<div class="row">

    <div class="col-md-6">
        <fieldset>
            <legend><?= Lang::get('users.beveiliging') ?></legend>
        </fieldset>
    </div>

</div>


<?= $creatorOrganisations ?>

<?= $creatorLocations ?>

@stop