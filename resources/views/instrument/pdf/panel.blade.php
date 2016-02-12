<div class="survey-panel {{ 'panel-' . $panel->color }}">

    <h3 class="title">{{ $panel->title }}</h3>
    @foreach($panel->questions as $question)

        <div class="question-wrapper {{ $question->summary_question ? 'summary-question' : null}}">

            <div class="question"><i class="fa fa-quote-left"></i>{{ $question->title }}</div>

            <?php $answer = $session->getAnswered($question) ?>

            @if($answer && $answer->wasFilledIn())

                @if($question->multiple_choise)

                    {{--checkboxes: only show when checked--}}
                    @if($question->multiple_answer)

                        <ul class="choises">
                            @foreach($answer->question->choises as $choise)
                                @if($answer->wasChecked($choise))
                                    <li class="box checked">{{ $choise->title }}</li>
                                @else
                                    <li class="box unchecked">{{ $choise->title }}</li>
                                @endif

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