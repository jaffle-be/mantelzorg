@section('content')

<h3><?= Lang::get('instellingen.profiel-hulpverlener') ?></h3>

<form action="">
    <div class="row">
        <div class="col-md-6">
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
            
            <label for="email"><?= Lang::get('login.email') ?></label>
            <span class="error"><?= $errors->first('email') ?></span>
            <div class="input-group">
                <?= Form::text('email', null, array('class' => 'form-control')) ?>
                <span class="input-group-addon">&nbsp;@</span>
            </div>

            <label for="password"><?= Lang::get('instellingen.password') ?></label>
            <span class="errors"><?= $errors->first('password') ?></span>

            <div class="input-group">
                <input class="form-control" type="password" name="password" id="password"/>
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            </div>

            <label for="phone"><?= Lang::get('instellingen.phone') ?></label>
            <span class="errors"><?= $errors->first('phone') ?></span>
            <div class="input-group">
                <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
            </div>

        </div>

        <div class="col-md-6">
            <label for="organisation"><?= Lang::get('instellingen.organisation') ?></label>
            <span class="errors"><?= $errors->first('organisation') ?></span>
            <div class="input-group">
                <?= Form::text('organisation', null, array('class' => 'form-control')) ?>
                <span class="input-group-addon"><i class="glyphicons group"></i></span>
            </div>


            <label for="street"><?= Lang::get('instellingen.street') ?></label>
            <span class="errors"><?= $errors->first('street') ?></span>
            <div class="input-group">
                <?= Form::text('street', null, array('class' => 'form-control', 'placeholder' => Lang::get('instellingen.street-placeholder'))) ?>
                <span class="input-group-addon"><i class="glyphicons group"></i></span>
            </div>

            <label for="city"><?= Lang::get('instellingen.city') ?></label>
            <span class="errors"><?= $errors->first('city') ?></span>
            <div class="input-group">
                <?= Form::text('city', null, array('class' => 'form-control', 'placeholder' => Lang::get('instellingen.city-placeholder'))) ?>
                <span class="input-group-addon"><i class="glyphicons group glyphicon-group"></i></span>
            </div>
        </div>
        
    </div>
    
    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?= Lang::get('instellingen.save-profile') ?>"/>
    </div>
</form>

<h3><?= Lang::get('instellingen.mantelzorgers') ?></h3>

<p>

</p>

@stop