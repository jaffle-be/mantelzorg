<div class="panel">

    <h3>{{ $panel->title }}</h3>
    @foreach($panel->questions as $question)

        @if($question->summary_question)

            <div class="question">
                <span>{{ $question->question }}</span>

                @if($answer = $session->getAnswered($question))
                    <span>{{ $answer->answer }}</span>

                @endif
            </div>


        @endif

    @endforeach

</div>