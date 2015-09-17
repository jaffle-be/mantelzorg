<div class="instrument-footer panel-{{$panel->color}}">

    <div class="row">

        @if($fullScreen)
            <div class="previous col-xs-3 col-sm-2">
                <button data-trigger="previous-question" class="btn btn-instrument" style="display:none">
                    <i class="fa fa-arrow-left"></i>{{ UI::isTablet() ? Lang::get('instrument.previous-question') : null}}
                </button>
            </div>
        @endif

        <div class="middle {{ $fullScreen ? 'col-xs-6 col-sm-8' : 'col-xs-12' }}">

            @if(UI::isTablet())
                <div class="question-list">
                    <?
                    $question = $panel->questions->first();

                    $answer = $survey->getAnswered($question);

                    $filled = $answer && $answer->wasFilledIn();
                    ?>
                    <h4>
                        <span class="title">{{ $question->title }}</span><span class="pull-right"><i class="fa fa-caret-down">&nbsp;</i></span>
                    </h4>

                    <ul style="display:none">
                        <?
                        $counter = 1;
                        $count = $panel->questions->count();
                        ?>
                        @foreach($panel->questions as $question)
                            <?
                            $answer = $survey->getAnswered($question);

                            $filled = $answer && $answer->wasFilledIn();
                            ?>
                            <li data-target-position="{{ $counter }}" {{ $counter == 1 ? 'class="active"' : null }} data-question-id="{{ $question->id }}">

                                <span class="pull-left">
                                    <i class="fa fa-question-circle"{{ $filled ? 'style="display:none"' : null }}></i>
                                    <i class="fa fa-check"{{ $filled ? null: 'style="display:none"' }}></i>
                                </span>

                                <span class="title" data-title>
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

            @else
                <input class="btn btn-instrument" type="submit" value="{{ Lang::get('instrument.bevestigen') }}"/>
            @endif

            <input type="hidden" id="next_panel" name="next_panel"/>

        </div>

        @if($fullScreen)
            <div class="next col-xs-3 col-sm-2">
                <div data-trigger="next-question">
                    <button class="btn btn-instrument">
                        {{ UI::isTablet() ? Lang::get('instrument.next-question'): null }}
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>

                @if(UI::isTablet())
                    <div data-trigger="confirm" style="display:none">
                        <button class="btn btn-instrument">
                            {{ Lang::get('instrument.bevestigen') }}
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                @endif
            </div>
        @endif


    </div>
</div>