@section('content')

<h3 xmlns="http://www.w3.org/1999/html"><?= Lang::get('instellingen.profiel-hulpverlener') ?></h3>

<form action="">
    <div class="row">
        <div class="col-md-5">

            <fieldset>
                <legend><?= Lang::get('instellingen.personal') ?></legend>
                <label for="firstname"><?= Lang::get('instellingen.firstname') ?></label>
                <span class="errors"><?= $errors->first('firstname') ?></span>
                <div class="input-group">
                <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                </div>

                <label for="lastname"><?= Lang::get('instellingen.lastname') ?></label>
                <span class="errors"><?= $errors->first('lastname') ?></span>
                <div class="input-group">
                    <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                </div>

                <div class="input-group">
                    <div class="radio-inline">
                        <label>
                            <?= Form::radio('male', 1) ?><?= Lang::get('instellingen.male') ?>&nbsp;<i class="glyphicons male"></i>
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label>
                            <?= Form::radio('male', 0) ?><?= Lang::get('instellingen.female') ?>&nbsp;<i class="glyphicons female"></i>
                        </label>
                    </div>
                </div>

                <label for="phone"><?= Lang::get('instellingen.phone') ?></label>
                <span class="errors"><?= $errors->first('phone') ?></span>
                <div class="input-group">
                    <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
                </div>

            </fieldset>
            
        </div>

        <div class="col-md-6 col-md-offset-1">
            <fieldset>
                <legend><?= Lang::get('instellingen.organisatie') ?></legend>
                <label for="organisation"><?= Lang::get('instellingen.organisation') ?></label>
                <span class="errors"><?= $errors->first('organisation') ?></span>
                <div class="input-group">
                    <?= Form::text('organisation', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons group"></i></span>
                </div>


                <label for="street"><?= Lang::get('instellingen.organisation-street') ?></label>
                <span class="errors"><?= $errors->first('street') ?></span>
                <div class="input-group">
                    <?= Form::text('street', null, array('class' => 'form-control', 'placeholder' => Lang::get('instellingen.organisation-street-placeholder'))) ?>
                    <span class="input-group-addon"><i class="glyphicons group"></i></span>
                </div>

                <label for="city"><?= Lang::get('instellingen.organisation-city') ?></label>
                <span class="errors"><?= $errors->first('city') ?></span>
                <div class="input-group">
                    <?= Form::text('city', null, array('class' => 'form-control', 'placeholder' => Lang::get('instellingen.organisation-city-placeholder'))) ?>
                    <span class="input-group-addon"><i class="glyphicons group glyphicon-group"></i></span>
                </div>
            </fieldset>
        </div>
        
    </div>

    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('instellingen.save-profile') ?>"/>
    </div>

    <div class="row">
        <div class="col-md-5">
            <fieldset>
                <legend><?= Lang::get('instellingen.security') ?></legend>
                <label for="email"><?= Lang::get('instellingen.email') ?></label>
                <span class="error"><?= $errors->first('email') ?></span>
                <div class="input-group">
                    <?= Form::text('email', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons envelope"></i></span>
                </div>

                <label for="password"><?= Lang::get('instellingen.password') ?></label>
                <span class="errors"><?= $errors->first('password') ?></span>

                <div class="input-group">
                    <input class="form-control" type="password" name="password" id="password"/>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                </div>
            </fieldset>
        </div>
    </div>
    
</form>

<h3><?= Lang::get('instellingen.mantelzorgers') ?></h3>

<p>

</p>

@stop