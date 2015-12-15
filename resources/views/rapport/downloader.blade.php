<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">{{ Lang::get('rapport.rapporten') }}</h3>
    </div>

    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-striped table-hover" data-trigger="rapport-list">

                <thead>
                <tr>
                    <th>
                        <div class="dropdown actions">
                            <a id="actions" class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">{{ Lang::get('master.tools.acties') }}&nbsp;<span class="caret">&nbsp;</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a id="select-all" class="select-all" href="">{{ Lang::get('master.tools.select_all') }}</a>
                                </li>
                                <li>
                                    <a id="select-none" class="select-none" href="">{{ Lang::get('master.tools.select_none') }}</a>
                                </li>
                                <li class="divider"></li>
                                {{--<li><a id="regen-password" class="regen-password" href="">{{ Lang::get('users.regen-password') }}</a></li>--}}
                                {{--<li class="divider"></li>--}}
                                <li><a id="remove" class="remove" href="#">{{ Lang::get('master.tools.remove') }}</a>
                                </li>
                            </ul>
                        </div>
                    </th>
                    <th><?= Lang::get('rapport.filename') ?></th>
                    <th><?= Lang::get('rapport.survey-count') ?></th>
                    <th><?= Lang::get('rapport.survey') ?></th>
                    <th><?= Lang::get('rapport.organisation') ?></th>
                    <th><?= Lang::get('rapport.user') ?></th>
                    <th><?= Lang::get('rapport.created') ?></th>
                    <th><?= Lang::get('rapport.download') ?></th>
                </tr>
                </thead>


                <tbody>
                <?php $teller = 1 ?>
                @foreach($reports as $report)

                    <tr>
                        <td>
                            <div class="checkbox">
                                <label class="control-label" for="row{{$teller}}">
                                    <input type="checkbox" id="row{{$teller}}" value="{{$report->id}}"/>
                                    {{ $teller }}
                                </label>
                            </div>

                        </td>
                        <td>{{ $report->filename }}</td>
                        <td>{{ $report->survey_count == -1 ? '?' : $report->survey_count }}</td>
                        <td>{{ $report->questionnaire->title }}</td>
                        <td>
                            @if($report->organisation)
                                {{ $report->organisation->name }}
                            @endif
                        </td>
                        <td>
                            @if($report->user)
                                {{ $report->user->fullname }}
                            @endif
                        </td>
                        <td>
                            {{ $report->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            <a title="{{ Lang::get('rapport.download') }}" href="{{ route('report.show', array($report)) }}">{{ Lang::get('rapport.download') }}</a>
                        </td>
                    </tr>

                    <?php $teller++ ?>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>