@section('scripts')
    <script src="/js/instrument.index.min.js"></script>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array('text' => Lang::get('master.navs.instrument')),
            array('text' => Lang::get('master.navs.start'))
    )) ?>
@stop

@section('content')

    @if(Auth::user()->admin || Session::get('hijack-original'))

        <form method="POST" action="{{ route('import') }}" enctype="multipart/form-data">

            <p class="clearfix">
                <input type="file" name="import" class="pull-left" style="margin-right:20px;"/>

                <input class="btn btn-warning pull-left" type="submit" value="{{ Lang::get('master.tools.import') }}"/>
            </p>

        </form>

    @endif

    <?= Form::open(array(
            'class'  => 'form-horizontal',
            'id'     => 'creator-form',
            'name'   => 'instrument-persons',
            'method' => 'POST',
            'route'  => 'instrument.submit'
    )); ?>

    <div class="well">{{ Lang::get('instrument.introduction') }}</div>

    <div class="row">

        <div class="col-md-6">
            <?= Form::select(
                    'mantelzorger', array('' => Lang::get('instrument.kies_mantelzorger')) + $hulpverlener->mantelzorgers->sortBy(function ($item) {
                        return $item->displayName;
                    })->lists('displayName', 'id'), null, array(
                            'id'    => 'mantelzorger-select',
                            'class' => 'form-control'
                    )
            ) ?>

        </div>

        <div class="col-md-6">
            <?= Form::select('oudere', array(), null, array(
                    'class' => 'form-control hide',
                    'id'    => 'ouderen-select'
            )) ?>
        </div>

    </div>

    <div class="row page-actions">
        <div class="col-md-12">
            <div class="alert alert-danger" style="display:none;"><?= Lang::get('instrument.need_persons_selected') ?></div>
        </div>
    </div>

    <div class="row page-actions">
        <div class="col-md-12">
            <input class="btn btn-primary" type="submit" value="<?= Lang::get('master.general.confirm') ?>"/>
        </div>
    </div>

    <?= Form::close() ?>

    <? if(count($surveys)): ?>

    @include('layout.easy-search-top', ['view' => 'instrument.search', 'data' => $surveys])

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>
                    <div class="dropdown actions">
                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">{{ Lang::get('master.tools.acties') }}&nbsp;<span class="caret">&nbsp;</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="select-all" href="">{{ Lang::get('master.tools.select_all') }}</a></li>
                            <li><a class="select-none" href="">{{ Lang::get('master.tools.select_none') }}</a></li>
                            @if(Auth::user()->admin || Session::get('hijack-original'))
                                <li class="divider"></li>
                                <li><a class="export" href="">{{ Lang::get('master.tools.export') }}</a></li>
                            @endif
                            <li class="divider"></li>
                            <li><a class="remove" href="">{{ Lang::get('master.tools.remove') }}</a></li>
                        </ul>
                    </div>
                </th>
                <th><?= Lang::get('instrument.mantelzorger') ?></th>
                <th><?= Lang::get('instrument.oudere') ?></th>
                <th><?= Lang::get('instrument.afgewerkt') ?></th>
                <th><?= Lang::get('instrument.created') ?></th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <? $teller = 1; ?>
            <? foreach($surveys as $survey): ?>
            <tr>
                <td>
                    {{$teller}} <input type="checkbox" value="{{$survey->id}}"/>
                </td>
                <td><?= $survey->mantelzorger->displayName ?></td>
                <td><?= $survey->oudere->displayName ?></td>
                <td>
                    @if($survey->isFinished())
                        <i class="fa fa-check-square-o"></i>
                    @endif
                </td>
                <td><?= $survey->created_at->format('d/m/Y') ?></td>
                <td>
                    <a href="<?= URL::route('instrument.panel.get', array($survey->questionnaire->panels->first()->id, $survey->id)) ?>"><?= Lang::get('instrument.werkverder') ?></a>
                </td>
                <td>
                    <a href="<?= URL::route('instrument.download', [$survey->id]) ?>"><i class="fa fa-cloud-download"></i></a>
                </td>
            </tr>
            <? $teller++ ?>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>

    @include('layout.easy-search-bottom', ['data' => $surveys])

    <? endif ?>

@stop