<div class="modal fade" id="locations-creator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Lang::get('hulpverleners.new_location') ?></h4>
            </div>
            <div class="modal-body">

                <form action="">
                    <label for=""><?= Lang::get('hulpverleners.location_name') ?></label>
                    <div class="alert alert-danger hide" data-target="name"></div>
                    <?= Form::text('name', null, array(
                        'class' => 'form-control'
                    )) ?>

                    <label for=""><?= Lang::get('hulpverleners.street') ?></label>
                    <div class="alert alert-danger hide" data-target="street"></div>
                    <?= Form::text('street', null, array(
                        'class' => 'form-control'
                    )) ?>

                    <label for=""><?= Lang::get('hulpverleners.postal') ?></label>
                    <div class="alert alert-danger hide" data-target="postal"></div>
                    <?= Form::text('postal', null, array(
                        'class' => 'form-control'
                    )) ?>

                    <label for=""><?= Lang::get('hulpverleners.city') ?></label>
                    <div class="alert alert-danger hide" data-target="city"></div>
                    <?= Form::text('city', null, array(
                        'class' => 'form-control'
                    )) ?>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Lang::get('hulpverleners.cancel') ?></button>
                <button type="button" class="btn btn-primary"><?= Lang::get('hulpverleners.confirm') ?></button>
            </div>
        </div>
    </div>
</div>