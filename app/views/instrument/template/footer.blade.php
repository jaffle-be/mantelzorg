<div class="instrument-footer panel-{{$panel->color}}">

    <div class="row">

        @if($fullScreen)
            <div class="previous col-xs-3">
                <button data-trigger="previous-question" class="btn btn-instrument" style="display:none;">
                    <i class="fa fa-arrow-left"></i>{{ Agent::isTablet() ? Lang::get('instrument.previous-question') : null}}
                </button>
            </div>
        @endif

        <div class="middle {{ $fullScreen ? 'col-xs-6' : 'col-xs-12' }}">

            <div class="question-list">
                <?
                $question = $panel->questions->first();

                $answer = $survey->getAnswered($question);

                $filled = $answer && $answer->wasFilledIn();
                ?>
                <h4><span class="title">{{ $question->title }}</span> <span class="pull-right"><i class="fa fa-caret-down">&nbsp;</i></span></h4>

                <ul style="display:none;">
                    <?
                    $counter = 1;
                    $count = $panel->questions->count();
                    ?>
                    @foreach($panel->questions as $question)
                        <?
                        $answer = $survey->getAnswered($question);

                        $filled = $answer && $answer->wasFilledIn();
                        ?>
                        <li data-question-id="{{ $question->id }}">
                            <i class="fa fa-question-circle"{{ $filled ? 'style="display:none;"' : null; }}></i>
                            <i class="fa fa-check"{{ $filled ? null: 'style="display:none;"' }}></i>
                            <span data-title>
                                {{ $question->title }}
                            </span>

                            <span class="count">
                            {{ $counter . ' / ' . $count }}
                        </span>
                        </li>

                        <?
                        $first = false;
                        $counter++;
                        ?>
                    @endforeach

                    <li data-trigger="close">
                        {{ Lang::get('instrument.close') }}
                        <span class="pull-right"><i class="fa fa-caret-down">&nbsp;</i></span>
                    </li>
                </ul>
            </div>

            <input type="hidden" id="next_panel" name="next_panel"/>
        </div>

        @if($fullScreen)
            <div class="next col-xs-3">
                <button data-trigger="next-question" class="btn btn-instrument">
                    {{ Agent::isTablet() ? Lang::get('instrument.next-question'): null }}
                    <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        @endif

    </div>
</div>