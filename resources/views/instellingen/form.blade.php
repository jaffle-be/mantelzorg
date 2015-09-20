<?= Form::model($user, array(
        'route'  => array('instellingen.update', $user->id),
        'method' => 'put',
)) ?>

<div class="row">

    <div class="col-sm-6 col-lg-3">

        <div class="card shadow-z-1">

            <div class="card-body">
                <fieldset>
                    <legend><?= Lang::get('users.beveiliging') ?></legend>


                    <div class="form-group">
                        <label class="control-label" for="current-password"><?= Lang::get('users.current-password') ?></label>

                        <div class="input-group">
                            <input class="form-control" type="password" name="current-password" id="current-password"/>
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        </div>

                        @error('current-password')
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="password"><?= Lang::get('users.new-password') ?></label>

                        <div class="input-group">
                            <input class="form-control" type="password" name="password" id="password"/>
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        </div>

                        @error('password')
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="password_confirmation"><?= Lang::get('users.password-confirmation') ?></label>

                        <div class="input-group">
                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation"/>
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        </div>

                        @error('password_confirmation')
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="email"><?= Lang::get('users.email') ?></label>
                        <div class="input-group">
                            <?= Form::text('email', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                        </div>

                        @error('email')
                    </div>


                </fieldset>
            </div>
        </div>

    </div>

    <div class="col-sm-6 col-lg-3">

        <div class="card shadow-z-1">

            <div class="card-body">
                <fieldset>
                    <legend><?= Lang::get('users.persoonlijk') ?></legend>

                    <div class="form-group">

                        <label class="control-label" for="firstname"><?= Lang::get('users.firstname') ?></label>

                        <div class="input-group">
                            <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>

                        @error('firstname')
                    </div>

                    <div class="form-group">

                        <label class="control-label" for="lastname"><?= Lang::get('users.lastname') ?></label>

                        <div class="input-group">
                            <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>

                        @error('lastname')

                    </div>

                    <div class="form-group">
                        <label class="control-label">{{ Lang::get('users.sex') }}</label>

                        <div>

                            <div class="radio radio-inline radio-success">
                                <label class="control-label">
                                    <?= Form::radio('male', 1) ?><?= Lang::get('users.male') ?>&nbsp;<i class="fa fa-male"></i>
                                </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <label class="control-label">
                                    <?= Form::radio('male', 0) ?><?= Lang::get('users.female') ?>&nbsp;<i class="fa fa-female"></i>
                                </label>
                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label class="control-label" for="phone"><?= Lang::get('users.phone') ?></label>

                        <div class="input-group">
                            <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        </div>

                        @error('phone')

                    </div>

                </fieldset>
            </div>

        </div>

    </div>

    <div class="col-sm-6 col-lg-3">

        <div class="card shadow-z-1">

            <div class="card-body">

                <fieldset>
                    <legend><?= Lang::get('users.organisatie') ?></legend>


                    <div class="form-group">
                        <div class="input-select">

                            <label class="control-label" for="organisation">
                                <?= Lang::get('users.organisatie') ?>
                            </label>

                            <?= Form::select('organisation_id', $organisations, null, array(
                                            'id'    => 'organisation',
                                            'class' => 'form-control')
                            )?>
                        </div>
                    </div>

                    @error('organisation_id')

                    <div class="form-group">
                        <div class="input-select">
                            <label class="control-label" for="locations"><?= Lang::get('users.locations') ?></label>
                            <?= Form::select('organisation_location_id', $locations, null, array(
                                    'id'    => 'location',
                                    'class' => 'form-control',
                            )) ?>
                        </div>
                    </div>

                    @error('organisation_location_id')

                </fieldset>

            </div>

        </div>

    </div>

</div>

<div class="form-actions">
    <button class="btn btn-primary" type="submit"><?= Lang::get('users.save') ?></button>
</div>

</form>