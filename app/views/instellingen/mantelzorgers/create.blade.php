@section('styles')
<link rel="stylesheet" href="/css/users.min.css"/>
@stop

@section('content')

<?= $subnav ?>


<?= Form::open(array('route' => 'mantelzorgers.store', 'method' => 'post')) ?>

<div class="row">
    <div class="col-md-6">

        <label for="email"><?= Lang::get('users.email') ?></label>
        <span class="errors"><?= $errors->first('email') ?></span>
        <div class="input-group">
            <?= Form::text('email', null, array('class' => 'form-control')) ?>
            <span class="input-group-addon">@</span>
        </div>

        <label for="firstname"><?= Lang::get('users.firstname') ?></label>
        <span class="errors"><?= $errors->first('firstname') ?></span>
        <div class="input-group">
            <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        </div>

        <label for="lastname"><?= Lang::get('users.lastname') ?></label>
        <span class="errors"><?= $errors->first('lastname') ?></span>
        <div class="input-group">
            <?= Form::text('lastname', null , array('class' => 'form-control')) ?>
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        </div>


        <div class="row">

            <div class="col-md-6">
                <label for="birthday"><?= Lang::get('users.birthday') ?></label>

                <span class="block errors"><?= $errors->first('birthday') ?></span>

                <div class="input-group">
                    <?= Form::text('birthday', null, array('class' => 'form-control datepicker')) ?>
                    <span class="input-group-addon"><i class="glyphicons birthday_cake"></i></span>
                </div>
            </div>

            <div class="col-md-6">

                <span class="block errors"><?= $errors->first('male') ?></span>

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
            </div>
        </div>

    </div>

    <div class="col-md-6">

        <label for="street"><?= Lang::get('users.street') ?></label>
        <span class="errors"><?= $errors->first('street') ?></span>
        <div class="input-group">
        <?= Form::text('street', null, array('class' => 'form-control')) ?>
            <span class="input-group-addon"><i class="glyphicons road"></i></span>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label for="postal"><?= Lang::get('users.postal') ?></label>
                <div class="input-group">
                    <?= Form::text('postal', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons road"></i></span>
                </div>
                <span class="block errors"><?= $errors->first('postal') ?></span>
            </div>

            <div class="col-md-8">
                <label for="city"><?= Lang::get('users.city') ?></label>
                <div class="input-group">
                    <?= Form::text('city', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons road"></i></span>
                </div>
                <span class="block errors"><?= $errors->first('city') ?></span>
            </div>
        </div>

        <label for="phone"><?= Lang::get('users.phone') ?></label>
        <span class="errors"><?= $errors->first('phone') ?></span>
        <div class="input-group">
            <?= Form::text('phone', null, array('class' => 'form-control')) ?>
            <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
        </div>


    </div>

</div>

<div>
<input class="btn btn-success" type="submit" value="<?= Lang::get('users.create_mantelzorger') ?>"/>
</div>


<?= Form::close() ?>


@stop