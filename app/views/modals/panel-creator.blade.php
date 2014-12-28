<div class="modal fade" id="panel-creator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Lang::get('questionnaires.new') ?></h4>
            </div>
            <div class="modal-body">

                <?= Form::open(array(
                        'method' => 'post',
                        'class'  => 'form-horizontal',
                )) ?>

                <input id="questionnaire-id" type="hidden"/>

                <label for="title"><?= Lang::get('questionnaires.panel-name') ?></label>
                @if($errors->has('title'))
                    <span class="errors">{{ $errors->first('title') }}</span>
                @endif
                <div class="input-group">
                    <?= Form::text('title', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons tag"></i></span>
                </div>

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


