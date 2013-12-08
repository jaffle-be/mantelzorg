@section('content')

    <?= Template::crumb(array(
        array(
            'text' => Lang::get('master.navs.questionnaires'),
        ),

        array('text' => Lang::get('master.navs.overzicht'))
    )) ?>

<div class="page-actions">
    <button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i><?= Lang::get('questionnaires.new_questionnaire') ?></button>
</div>


    @if(count($questionnaires))

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?= Lang::get('questionaire.questionnaire_name') ?></th>
                    <th><?= Lang::get('questionaire.active') ?></th>
                </tr>
            </thead>

            <tbody>

                <? $teller = 1; ?>
                @foreach($questionnaires as $questionnaire)
                <tr>
                    <td><?= $teller; ?></td>
                    <td><?= $questionnaire->title ?></td>
                    <td>
                        @if($questionnaire->active === '1')
                        <i class="glyphicons-icon ok_2">&nbsp;</i>
                        @endif
                    </td>
                </tr>
                <? $teller++ ?>
                @endforeach

            </tbody>
        </table>

    @else
    {{ Lang::get('questionnaires.no_questionaires') }}
    @endif


<?= $questionnaireCreator ?>

@stop