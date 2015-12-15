@if($mode == 'create')
    <?php $oudere = new App\Mantelzorger\Oudere(); ?>
    <?= Form::open(array(
            'route' => array('instellingen.{mantelzorger}.oudere.store', $mantelzorger->id),
            'method' => 'post',
    )) ?>
@else
    <?= Form::model($oudere, array(
            'route' => array('instellingen.{mantelzorger}.oudere.update', $mantelzorger->id, $oudere->id),
            'method' => 'put',
    )) ?>
@endif

<div class="row">

    <div class="col-md-12">

        <div class="card shadow-z-1">
            <div class="card-body">

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
                                            <?= Form::text('birthday', $oudere->birthday ? $oudere->birthday->format('d/m/Y') : null, array('class' => 'form-control datepicker')) ?>
                                            <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                                        </div>

                                        @error('birthday')
                                    </div>

                                </div>

                                <div class="col-md-6 gender">

                                    <div class="form-group">

                                        <label class="control-label">{{ Lang::get('users.sex') }}</label>

                                        <div class="input-group">
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
                                @error('email')
                                <div class="input-group">
                                    <?= Form::text('email', null, array('class' => 'form-control')) ?>
                                    <span class="input-group-addon">@</span>
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


                        </div>

                    </div>

                </fieldset>

            </div>
        </div>

        <div class="card shadow-z-1">

            <div class="card-body">

                <fieldset>

                    <legend><?= Lang::get('users.dossier_details') ?></legend>

                    <div class="row">
                        <div class="col-xs-12 col-md-6">

                            <div class="form-group">

                                <label class="control-label" for="mantelzorger_relation_id"><?= Lang::get('users.relatie_mantelzorger') ?></label>

                                <?= Form::select('mantelzorger_relation_id', $relations_mantelzorger, $oudere->mantelzorger_relation_id, array('id' => 'mantelzorger_relation_id', 'class' => 'form-control')) ?>

                            </div>

                            @error('mantelzorger_relation')

                            <div class="form-group">

                                <label class="control-label" for="mantelzorger_relation_id_alternate"><?= Lang::get('users.relatie_mantelzorger_alternate') ?></label>

                                <?= Form::text('mantelzorger_relation_id_alternate', null, array(
                                        'id' => 'mantelzorger_relation_id_alternate',
                                        'class' => 'form-control',
                                )) ?>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="details_diagnose"><?= Lang::get('users.details_diagnose') ?></label>

                                <?= Form::textarea('details_diagnose', null, array('id' => 'details_diagnose', 'class' => 'form-control')) ?>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">

                            <div class="form-group">

                                <label class="control-label" for="woonsituatie_id">{{ Lang::get('users.woonsituatie') }}</label>

                                <?= Form::select('woonsituatie_id', $woonsituaties, null, array('id' => 'woonsituatie_id', 'class' => 'form-control')) ?>

                            </div>

                            @error('woonsituatie_id')

                            <div class="form-group">
                                <label class="control-label" for="oorzaak_hulpbehoefte_id"><?= Lang::get('users.oorzaak_hulpbehoefte') ?></label>

                                <?= Form::select('oorzaak_hulpbehoefte_id', $hulpbehoeftes, null, array('id' => 'oorzaak_hulpbehoefte_id', 'class' => 'form-control')) ?>

                            </div>

                            @error('oorzaak_hulpbehoefte_id')

                            <div class="form-group">
                                <label class="control-label" for="bel_profiel_id"><?= Lang::get('users.bel_profiel') ?></label>

                                <?= Form::select('bel_profiel_id', $belprofielen, null, array('id' => 'bel_profiel_id', 'class' => 'form-control')) ?>
                            </div>
                        </div>
                    </div>

                </fieldset>

            </div>

        </div>

    </div>

</div>

<div>
    <button class="btn btn-primary" type="submit"><?= Lang::get('users.save') ?></button>
</div>

<?= Form::close() ?>