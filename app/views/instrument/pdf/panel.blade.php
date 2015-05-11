<div class="survey-panel {{ 'panel-' . $panel->color }}">

    <h3 class="title">{{ $panel->title }}</h3>
    @foreach($panel->questions as $question)

        <div class="question-wrapper {{ $question->summary_question ? 'summary-question' : null}}">

            <div class="question"><i class="fa fa-quote-left"></i>{{ $question->title }}</div>


            @if($answer = $session->getAnswered($question))

                @if($question->multiple_choise)

                    <ul class="choises">
                        @foreach($answer->choises as $choise)

                            <li>{{ $choise->title }}</li>

                        @endforeach
                    </ul>

                @endif


                @if($answer->explanation)
                    <p class="explanation">{{ $answer->explanation }}</p>
                @endif

            @endif

        </div>



    @endforeach

</div>