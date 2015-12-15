@if($mode == 'create')
    <?php $mantelzorger = new App\Mantelzorger\Mantelzorger(); ?>
    <?= Form::open(array('route' => array('instellingen.{hulpverlener}.mantelzorgers.store', $hulpverlener->id), 'method' => 'post')) ?>
@else
    <?= Form::model($mantelzorger, array(
            'route' => array('instellingen.{hulpverlener}.mantelzorgers.update', $hulpverlener->id, $mantelzorger->id),
            'method' => 'put',
    )) ?>
@endif

<div class="card shadow-z-1">

    <div class="card-body">

        <div class="row">

            <div class="col-md-12">

                <fieldset>

                    <legend><?= Lang::get('users.persoonlijk') ?></legend>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="control-label" for="identifier"><?= Lang::get('users.identifier') ?></label>

                                <div class="input-group">
                                    <?= Form::text('identifier', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                </div>

                                @error('identifier')

                            </div>

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


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="birthday"><?= Lang::get('users.birthday') ?></label>

                                        <div class="input-group">
                                            <?= Form::text('birthday', $mantelzorger->birthday ? $mantelzorger->birthday->format('d/m/Y') : null, array('class' => 'form-control datepicker')) ?>
                                            <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                                        </div>

                                        @error('birthday')
                                    </div>
                                </div>

                                <div class="col-md-6 gender">

                                    <div class="form-group">

                                        <label class="control-label">{{ Lang::get('users.sex') }}</label>

                                        <div class="input-group">
                                            <div class="radio radio-success radio-inline">
                                                <label class="control-label">
                                                    <?= Form::radio('male', 1) ?><?= Lang::get('users.male') ?>&nbsp;<i class="fa fa-male"></i>
                                                </label>
                                            </div>
                                            <div class="radio radio-success radio-inline">
                                                <label class="control-label">
                                                    <?= Form::radio('male', 0) ?><?= Lang::get('users.female') ?>&nbsp;<i class="fa fa-female"></i>
                                                </label>
                                            </div>
                                        </div>

                                        @error('male')

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label" for="street"><?= Lang::get('users.street') ?></label>

                                <div class="input-group">
                                    <?= Form::text('street', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon"><i class="fa fa-road"></i></span>
                                </div>

                                @error('street')
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="postal"><?= Lang::get('users.postal') ?></label>

                                        <div class="input-group">
                                            <?= Form::text('postal', null, array('class' => 'form-control')) ?>
                                            <span class="input-group-addon"><i class="fa fa-road"></i></span>
                                        </div>

                                        @error('postal')
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">

                                        <label class="control-label" for="city"><?= Lang::get('users.city') ?></label>

                                        <div class="input-group">
                                            <?= Form::text('city', null, array('class' => 'form-control')) ?>
                                            <span class="input-group-addon"><i class="fa fa-road"></i></span>
                                        </div>

                                        @error('city')

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="email"><?= Lang::get('users.email') ?></label>

                                <div class="input-group">
                                    <?= Form::text('email', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon">@</span>
                                </div>

                                @error('email')
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

                    </div>

                </fieldset>

            </div>

        </div>

    </div>


</div>

<div>
    <button class="btn btn-primary" type="submit"><?= $buttonText ?></button>
</div>


<?= Form::close() ?>
