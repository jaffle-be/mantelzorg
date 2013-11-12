<div class="modal fade" id="organisation-creator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Lang::get('users.new_organisation') ?></h4>
            </div>
            <div class="modal-body">

                <form action="">
                    <label for="organisation_name"><?= Lang::get('users.organisation_name') ?></label>
                    <div class="alert alert-danger hide"></div>
                    <?= Form::text('organisation_name', isset($inschrijving) ? $inschrijving->organisation : '', array('class' => 'form-control')) ?>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Lang::get('master.general.cancel') ?></button>
                <button type="button" class="btn btn-primary"><?= Lang::get('master.general.confirm') ?></button>
            </div>
        </div>
    </div>
</div>