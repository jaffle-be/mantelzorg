<div class="modal fade" id="locations-creator">

    <form action="">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?= Lang::get('users.new_location_title') ?></h4>
                </div>
                <div class="modal-body">


                    <label class="control-label" for=""><?= Lang::get('users.location_name') ?></label>

                    <div id="error-name" class="alert alert-danger hide" data-target="name"></div>
                    <div class="input-group">
                        <?= Form::text('name', null, array(
                                'class' => 'form-control',
                        )) ?>
                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    </div>

                    <label class="control-label" for=""><?= Lang::get('users.street') ?></label>

                    <div id="error-street" class="alert alert-danger hide" data-target="street"></div>
                    <div class="input-group">
                        <?= Form::text('street', null, array(
                                'class' => 'form-control',
                        )) ?>
                        <span class="input-group-addon"><i class="fa fa-road"></i></span>
                    </div>

                    <div class="row">

                        <div class="col-md-4">

                            <label class="control-label" for=""><?= Lang::get('users.postal') ?></label>

                            <div id="error-postal" class="alert alert-danger hide" data-target="postal"></div>

                            <div class="input-group">
                                <?= Form::text('postal', null, array(
                                        'class' => 'form-control',
                                )) ?>
                                <span class="input-group-addon"><i class="fa fa-road"></i></span>
                            </div>

                        </div>

                        <div class="col-md-8">

                            <label class="control-label" for=""><?= Lang::get('users.city') ?></label>

                            <div id="error-city" class="alert alert-danger hide" data-target="city"></div>
                            <div class="input-group">
                                <?= Form::text('city', null, array(
                                        'class' => 'form-control',

                                )) ?>
                                <span class="input-group-addon"><i class="fa fa-road"></i></span>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Lang::get('master.general.cancel') ?></button>
                    <button id="create-location" type="button" class="btn btn-primary"><?= Lang::get('master.general.confirm') ?></button>
                </div>
            </div>
        </div>

    </form>
</div>