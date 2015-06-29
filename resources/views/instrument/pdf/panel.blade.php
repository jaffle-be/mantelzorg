<div class="survey-panel {{ 'panel-' . $panel->color }}">

    <h3 class="title">{{ $panel->title }}</h3>
    @foreach($panel->questions as $question)

        <div class="question-wrapper {{ $question->summary_question ? 'summary-question' : null}}">

            <div class="question"><i class="fa fa-quote-left"></i>{{ $question->title }}</div>

            <? $answer = $session->getAnswered($question) ?>

            @if($answer && $answer->wasFilledIn())

                @if($question->multiple_choise)

                    {{--checkboxes: only show when checked--}}
                    @if($question->multiple_answer)

                        <ul class="choises">
                            @foreach($answer->question->choises as $choise)

                                <li class="box {{ $answer->wasChecked($choise) ? 'checked': null }}">{{ $choise->title }}</li>

                            @endforeach
                        </ul>
                    {{--radios: show all--}}
                    @else

                        <ul class="choises">
                            @foreach($answer->question->choises as $option)

                                <li class="radio {{ $option->id == $answer->choises->first()->id ? 'checked' : null }}">{{ $option->title }}</li>

                            @endforeach
                        </ul>

                    @endif

                @endif


                @if($answer->explanation)
                    <p class="explanation">{{ $answer->explanation }}</p>
                @endif

            @endif

        </div>



    @endforeach

</div>