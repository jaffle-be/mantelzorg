<div class="modal fade" id="questionnaire-creator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Lang::get('questionnaires.new') ?></h4>
            </div>
            <div class="modal-body">

                <?= Form::open(array(
                        'method' => 'post',
                        'route' => 'survey.store',
                        'class' => 'form-horizontal',
                )) ?>


                <label class="control-label" for="title"><?= Lang::get('questionnaires.name') ?></label>

                <div class="input-group">
                    <?= Form::text('title', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                </div>

                @error('title')

                <?= Form::close() ?>

                <div class="alert alert-danger hide">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Lang::get('master.general.cancel') ?></button>
                <button type="button" class="btn btn-primary"><?= Lang::get('master.general.confirm') ?></button>
            </div>
        </div>
    </div>
</div>


