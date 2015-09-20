<?= Form::open(['route' => ['inschrijvingen.update', $inschrijving->id], 'method' => 'put', 'id' => 'create-user-form']) ?>

<div class="card shadow-z-1">

    <div class="card-body">
        <div class="row">

            <div class="col-md-5">
                <div class="form-group">
                    <input name="id" type="hidden" value="<?= $inschrijving->id ?>"/>

                    <label class="control-label" for="email"><?= Lang::get('users.email') ?></label>

                    <div class="input-group">
                        <?= Form::text('email', $inschrijving->email, array('class' => 'form-control')) ?>
                        <span class="input-group-addon">@</span>
                    </div>

                    @error('email')
                </div>

                <div class="form-group">
                    <label class="control-label" for="firstname"><?= Lang::get('users.firstname') ?></label>

                    <div class="input-group">
                        <?= Form::text('firstname', $inschrijving->firstname, array('class' => 'form-control')) ?>
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>

                    @error('firstname')
                </div>

                <div class="form-group">
                    <label class="control-label" for="lastname"><?= Lang::get('users.lastname') ?></label>

                    <div class="input-group">
                        <?= Form::text('lastname', $inschrijving->lastname, array('class' => 'form-control')) ?>
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>

                    @error('lastname')
                </div>


                <div class="form-group">

                    <label class="control-label">{{ Lang::get('users.sex') }}</label>

                    <div class="input-group">
                        <div class="radio radio-inline radio-success">
                            <label class="control-label">
                                <?= Form::radio('male', 1, ['id' => 'male']) ?><?= Lang::get('users.male') ?>&nbsp;<i class="fa fa-male"></i>
                            </label>
                        </div>
                        <div class="radio radio-inline radio-success">
                            <label class="control-label">
                                <?= Form::radio('male', 0, ['id' => 'female']) ?><?= Lang::get('users.female') ?>&nbsp;<i class="fa fa-female"></i>
                            </label>
                        </div>
                    </div>

                    @error('male')
                </div>

                <div class="form-group">
                    <label class="control-label" for="phone"><?= Lang::get('users.phone') ?></label>

                    <div class="input-group">
                        <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    </div>

                    @error('phone')
                </div>

            </div>

            <div class="col-md-5 col-md-offset-1">

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

                <div class="alert alert-info"><?= Lang::get('users.oorspronkelijke_organisatie', array('organisatie' => $inschrijving->organisation)) ?></div>

                <div class="form-group">
                    <div class="input-select holder <?= !Input::old('organisation_id') ? 'hide' : '' ?>">
                        <label class="control-label" for="locations"><?= Lang::get('users.locations') ?></label>

                        <?= Form::select('organisation_location_id', $locations, Input::old('organisation_location_id'), array(
                                'id'    => 'location',
                                'class' => 'form-control',
                        )) ?>

                    </div>
                </div>

                @error('organisation_location_id')

            </div>

        </div>

        <div>
            <button id="create-user" class="btn btn-primary" type="submit"><?= Lang::get('users.create_as_helper') ?></button>
        </div>

    </div>

</div>

</form>