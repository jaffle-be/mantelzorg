@section('content')

    <?= Template::crumb(array(
        array(
            'text' => Lang::get('master.navs.questionnaires'),
        ),

        array('text' => Lang::get('master.navs.overzicht'))
    )) ?>

    @if(count($questionnaires))

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?= Lang::get('questionnaires.name') ?></th>
                    <th><?= Lang::get('questionnaires.active') ?></th>
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
    {{ Lang::get('questionnaires.no_questionnaires') }}
    @endif



<div class="page-actions">
    <button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i><?= Lang::get('questionnaires.new') ?></button>
</div>


<?= $questionnaireCreator ?>

@stop