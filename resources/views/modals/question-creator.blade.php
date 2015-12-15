<div class="modal fade" id="question-creator">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Lang::get('questionnaires.new_question') ?></h4>
            </div>
            <div class="modal-body">

                <?= Form::open(['method' => 'post']) ?>

                <label class="control-label"><?= Lang::get('questionnaires.title') ?></label>

                <div class="input-group">
                    <?= Form::text('title', null, array('class' => 'form-control')) ?>
                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                </div>

                    <div id="error-title" class="alert alert-danger hide" data-target="title"></div>


                <label class="control-label"><?= Lang::get('questionnaires.question') ?></label>
                <?= Form::textarea('question', null, array('class' => 'form-control', 'style' => 'margin-bottom:10px;')) ?>

                    <div id="error-question" class="alert alert-danger hide" data-target="question"></div>

                <div class="checkbox">
                    <label class="control-label" id="summary_question">
                        <input type="checkbox" name="summary_question"/>
                        <?= Lang::get('questionnaires.summary_question') ?>
                    </label>
                </div>

                <div class="checkbox">
                    <label class="control-label" id="multiple_choise">
                        <input type="checkbox" name="multiple_choise"/>
                        <?= Lang::get('questionnaires.multiple_choise_question') ?>
                    </label>
                </div>

                <div class="checkbox">
                    <label class="control-label" id="explainable">
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


