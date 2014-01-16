@section('styles')
<link rel="stylesheet" href="<?= asset('css/users.min.css') ?>"/>
@stop

@section('content')

<?= Template::crumb(array(
    array(
        'text' => Lang::get('master.navs.instellingen'),
        'href' => URL::action('Instelling\PersonController@index')
    ),
    array(
        'text' => Lang::get('master.navs.mantelzorgers'),
        'href' => URL::route('instellingen.{hulpverlener}.mantelzorgers.index', array(Auth::user()->id))
    ),
    array(
        'text' => Lang::get('master.navs.ouderen')
    ),
    array(
        'text' => Lang::get('master.navs.wijzig')
    )

)) ?>


<?= Form::model($oudere, array(
    'route' => array('instellingen.{mantelzorger}.oudere.update', $mantelzorger->id, $oudere->id),
    'method' => 'put',
)) ?>

<div class="row">

    <div class="col-md-12">

        <fieldset>

            <legend><?= Lang::get('users.persoonlijk') ?></legend>

            <div class="row">

                <div class="col-md-6">

                    <label for="firstname"><?= Lang::get('users.firstname') ?></label>
                    @if($errors->has('firstname'))
                    <span class="errors">{{ $errors->first('firstname') }}</span>
                    @endif
                    <div class="input-group">
                        <?= Form::text('firstname', null, array('class' => 'form-control')) ?>
                        <span class="input-group-addon"><i class="glyphicons user"></i></span>
                    </div>

                    <label for="lastname"><?= Lang::get('users.lastname') ?></label>
                    @if($errors->has('lastname'))
                    <span class="errors">{{ $errors->first('lastname') }}</span>
                    @endif
                    <div class="input-group">
                        <?= Form::text('lastname', null, array('class' => 'form-control')) ?>
                        <span class="input-group-addon"><i class="glyphicons user"></i></span>
                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label for="birthday"><?= Lang::get('users.birthday') ?></label>

                            <div class="input-group">
                                <?= Form::text('birthday', $oudere->birthday->format('d/m/Y'), array('class' => 'form-control datepicker')) ?>
                                <span class="input-group-addon"><i class="glyphicons birthday_cake"></i></span>
                            </div>

                            @if($errors->has('birthday'))
                            <span class="errors">
                                {{ $errors->first('birthday') }}
                            </span>
                            @endif

                        </div>

                        <div class="col-md-6 gender">
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

                            <span class="block errors"><?= $errors->first('male') ?></span>
                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <label for="street"><?= Lang::get('users.street') ?></label>
                    @if($errors->has('street'))
                    <span class="errors">{{ $errors->first('street') }}</span>
                    @endif
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
                            @if($errors->has('postal'))
                            <span class="errors">{{ $errors->first('postal') }}</span>
                            @endif

                        </div>

                        <div class="col-md-8">

                            <label for="city"><?= Lang::get('users.city') ?></label>
                            <div class="input-group">
                                <?= Form::text('city', null, array('class' => 'form-control')) ?>
                                <span class="input-group-addon"><i class="glyphicons road"></i></span>
                            </div>
                            @if($errors->has('city'))
                            <span class="errors">{{ $errors->first('city') }}</span>
                            @endif

                        </div>
                    </div>

                    <label for="email"><?= Lang::get('users.email') ?></label>
                    @if($errors->has('email'))
                    <span class="errors">{{ $errors->first('email') }}</span>
                    @endif
                    <div class="input-group">
                        <?= Form::text('email', null, array('class' => 'form-control')) ?>
                        <span class="input-group-addon">@</span>
                    </div>

                    <label for="phone"><?= Lang::get('users.phone') ?></label>

                    @if($errors->has('phone'))
                    <span class="errors">{{ $errors->first('phone') }}</span>
                    @endif

                    <div class="input-group">

                        <?= Form::text('phone', null, array('class' => 'form-control')) ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>

                    </div>



                </div>

            </div>

        </fieldset>

    </div>

</div>

<div>
    <input class="btn btn-primary" type="submit" value="<?= Lang::get('users.save') ?>"/>
</div>

<?= Form::close() ?>


@stop