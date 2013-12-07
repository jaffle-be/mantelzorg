@section('styles')
<link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('content')

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



<?= Form::open(array('action' => array('InschrijvingController@update', $inschrijving->id), 'method' => 'put')) ?>

    <div class="row">

        <div class="col-md-6">
            <input name="id" type="hidden" value="<?= $inschrijving->id ?>"/>

            <label for="email"><?= Lang::get('users.email') ?></label>
            <span class="errors"><?= $errors->first('email') ?></span>
            <div class="input-group">
                <?= Form::text('email', $inschrijving->email, array('class' => 'form-control')) ?>
                <span class="input-group-addon">@</span>
            </div>

            <label for="firstname"><?= Lang::get('users.firstname') ?></label>
            <span class="errors"><?= $errors->first('firstname') ?></span>
            <div class="input-group">
                <?= Form::text('firstname', $inschrijving->firstname, array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            </div>

            <label for="lastname"><?= Lang::get('users.lastname') ?></label>
            <span class="errors"><?= $errors->first('lastname') ?></span>
            <div class="input-group">
                <?= Form::text('lastname', $inschrijving->lastname , array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            </div>

            <span class="errors"><?= $errors->first('male') ?></span>

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

        </div>

        <div class="col-md-6">

            <div class="input-select">
                <label for="organisation">
                <?= Lang::get('users.organisatie') ?>
                </label>
                <span class="errors"><?= $errors->first('organisation_id') ?></span>

                <?= Form::select('organisation_id', $organisations, null, array(
                    'id' => 'organisation',
                    'class' => 'form-control')
                )?>
            </div>

            <p class="alert alert-info"><?= Lang::get('users.oorspronkelijke_organisatie', array('organisatie' => $inschrijving->organisation)) ?></p>

            <div class="input-select holder <?= !Input::old('organisation_id') ? 'hide' : '' ?>">
            <label for="locations"><?= Lang::get('users.locations') ?></label>
            <span class="errors"><?= $errors->first('organisation_location_id') ?></span>
            <?= Form::select('organisation_location_id', $locations, Input::old('organisation_location_id'), array(
                'id' => 'location',
                'class' => 'form-control',
            )) ?>
            </div>

        </div>

    </div>

    <div class="form-actions">
        <button class="btn btn-primary" type="submit"><?= Lang::get('users.create_as_helper') ?></button>
    </div>

</form>


<?= $creatorOrganisations ?>

<?= $creatorLocations ?>

@stop