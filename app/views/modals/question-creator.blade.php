<div class="modal fade" id="question-creator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Lang::get('questionnaires.new_question') ?></h4>
            </div>
            <div class="modal-body">

                <label><?= Lang::get('questionnaires.title') ?></label>
                @if($errors->has('title'))
                    <span class="errors">{{ $errors->first('title') }}</span>
                @endif
                <div class="input-group">
                    <?= Form::text('title', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="glyphicons tag"></i></span>
                </div>


                <label><?= Lang::get('questionnaires.question') ?></label>
                @if($errors->has('question'))
                    <span class="errors">{{ $errors->first('question') }}</span>
                @endif
                <?= Form::textarea('question', null, array('class' => 'form-control')) ?>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="summary_question" id=""/>
                        <?= Lang::get('questionnaires.summary_question') ?>
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="multiple_choise" id=""/>
                        <?= Lang::get('questionnaires.multiple_choise_question') ?>
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="explainable" id=""/>
                        <?= Lang::get('questionnaires.explainable') ?>
                    </label>
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


