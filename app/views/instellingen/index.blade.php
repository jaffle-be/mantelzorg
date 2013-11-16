@section('content')

<?= $subnav ?>

<?= Form::model($user, array(
    'action' => array('Instelling\PersonController@update', $user->id),
    'method' => 'put',
)) ?>

    <div class="row">
        <div class="col-md-5">

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

                <div>
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
                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
                </div>

            </fieldset>
            
        </div>

        <div class="col-md-6 col-md-offset-1">
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

    <div class="row">
        <div class="col-md-5">
            <fieldset>
                <legend><?= Lang::get('users.beveiliging') ?></legend>

                <label for="email"><?= Lang::get('users.email') ?></label>
                <span class="error"><?= $errors->first('email') ?></span>
                <div class="input-group">
                    <?= Form::text('email', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons envelope"></i></span>
                </div>
<? /**
                <label for="password"><?= Lang::get('users.password') ?></label>
                <span class="errors"><?= $errors->first('password') ?></span>

                <div class="input-group">
                    <input class="form-control" type="password" name="password" id="password"/>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                </div>
 */ ?>
            </fieldset>
        </div>
    </div>

    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('users.save') ?>"/>
    </div>
    
</form>

@stop