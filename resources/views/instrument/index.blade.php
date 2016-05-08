@extends('layout.admin.master')

@section('scripts')
    <script src="/js/instrument.index.min.js"></script>
@stop

@section('page-header')
    <?= Template::crumb(array(
            array('text' => Lang::get('master.navs.instrument')),
            array('text' => Lang::get('master.navs.start')),
    )) ?>
@stop

@section('content')

    <div class="card shadow-z-1">
        <div class="card-body">

            @if(Auth::user()->admin || Session::get('hijack-original'))

                <form method="POST" action="{{ route('import') }}" enctype="multipart/form-data">

                    <p class="clearfix">
                        <input type="file" name="import" class="pull-left" style="margin-right:20px;"/>

                        <button class="btn btn-warning pull-left" type="submit">{{ Lang::get('master.tools.import') }}</button>
                    </p>

                </form>

            @endif

            <?= Form::open(array(
                    'class' => '',
                    'id' => 'creator-form',
                    'name' => 'instrument-persons',
                    'method' => 'POST',
                    'route' => 'instrument.submit',
            )); ?>

            <div class="alert alert-info">{{ Lang::get('instrument.introduction') }}</div>

            <div class="row">

                    <div class="form-group col-md-6">
                    <?= Form::select(
                            'mantelzorger', array('' => Lang::get('instrument.kies_mantelzorger')) + $hulpverlener->mantelzorgers->sortBy(function ($item) {
                                return $item->displayName;
                            })->pluck('displayName', 'id')->all(), null, array(
                                    'id' => 'mantelzorger-select',
                                    'class' => 'form-control col-md-6',
                            )
                    ) ?>
                    </div>


                <div class="form-group col-md-6">
                    <?= Form::select('oudere', array(), null, array(
                            'class' => 'form-control hide col-md-6',
                            'id' => 'ouderen-select',
                    )) ?>
                </div>

            </div>

            <div class="alert alert-danger" style="display:none;"><?= Lang::get('instrument.need_persons_selected') ?></div>

            <button class="btn btn-primary" type="submit"><?= Lang::get('master.general.confirm') ?></button>

            <?= Form::close() ?>

        </div>
    </div>

    @include('layout.easy-search-top', ['view' => 'instrument.search', 'data' => $surveys])

    <?php if (count($surveys)): ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>
                    <div class="dropdown actions">
                        <a id="actions" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">{{ Lang::get('master.tools.acties') }}&nbsp;<span class="caret">&nbsp;</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="select-all" href="">{{ Lang::get('master.tools.select_all') }}</a></li>
                            <li><a class="select-none" href="">{{ Lang::get('master.tools.select_none') }}</a></li>
                            <li class="divider"></li>
                            <li><a class="batch-pdf" href="">{{ Lang::get('instrument.download') }}</a></li>
                            @if(Auth::user()->admin || Session::get('hijack-original'))
                                <li class="divider"></li>
                                <li><a class="export" href="">{{ Lang::get('master.tools.export') }}</a></li>
                            @endif
                            <li class="divider"></li>
                            <li><a id="remove" class="remove" href="#">{{ Lang::get('master.tools.remove') }}</a></li>
                        </ul>
                    </div>
                </th>
                <th><?= Lang::get('instrument.mantelzorger') ?></th>
                <th><?= Lang::get('instrument.oudere') ?></th>
                <th><?= Lang::get('instrument.afgewerkt') ?></th>
                <th><?= Lang::get('instrument.created') ?></th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php $teller = 1; ?>
            <?php foreach ($surveys as $survey): ?>
            <tr data-session-id="{{$survey->id}}">
                <td>
                    <div class="checkbox">
                        <label class="control-label" for="row{{$teller}}">
                            <input type="checkbox" id="row{{$teller}}" value="{{$survey->id}}"/>
                            {{$teller}}
                        </label>
                    </div>

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
                    <a href="<?= route('instrument.panel.get', array($survey->questionnaire->panels->first()->id, $survey->id)) ?>"><?= Lang::get('instrument.werkverder') ?></a>
                </td>
                <td>
                    <a href="<?= route('instrument.view', [$survey->id]) ?>"><i class="fa fa-eye"></i></a>
                </td>
                <td>
                    <a href="<?= route('instrument.download', [$survey->id]) ?>"><i class="fa fa-cloud-download"></i></a>
                </td>
            </tr>
            <?php $teller++ ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    @include('layout.easy-search-bottom', ['data' => $surveys])

    <?php endif ?>

@stop