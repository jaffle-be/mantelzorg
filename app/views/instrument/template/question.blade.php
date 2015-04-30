<?php

$answer = $survey->getAnswered($question);

$filled = $answer && $answer->wasFilledIn();

$mark = $filled ? 'display:none;' : null;

$check = $filled ? null : 'display:none;';

/**
 * first part: for mobiles or tablets, only show the first element
 * second part: for anything else then mobiles or tablets show the elements
 */
$show = ($first && (Agent::isMobile() ||  Agent::isTablet())) || !Agent::isMobile() && !Agent::isTablet() ? '' : 'display:none;';

?>

<div class="instrument-question question-{{$panel->color}}" data-question-id="{{ $question->id }}" style="{{ $show }}">

    {{--wrapper for borders on desktops--}}
    <div>
        {{--question header--}}
        <div class="header">

            {{--question status--}}
            <span class="question-status">

                    <i class="fa fa-question-circle" style="{{ $mark }}"></i>
                    <i class="fa fa-check" style="{{ $check }}"></i>

                </span>

            {{$question->title}}

            {{--question editing--}}

            <span class="question-editing">
                    <i data-show-on="not-editing" class="fa fa-pencil-square-o" {{ $first ? 'style="display:none;"' : '' }}></i>
                    <i data-show-on="editing" data-trigger="toggle-comment" title="{{ Lang::get('questionnaires.meta') }}" class="fa fa-comment" {{ $first ? '' : 'style="display:none;"' }}></i>
                </span>

        </div>

        {{--question body--}}
        <div class="body" {{ $first ? '' : 'style="display:none;"' }}>

            <div class="question">{{ $question->question }}</div>

            @if($question->meta)

                <div class="well well-sm" style="display:none;">{{ $question->meta }}</div>

            @endif

            {{--multiple choise--}}
            @if($question->multiple_choise == '1')

                <ul class="choises">

                    @if($question->multiple_answer == '1')
                        {{--checkboxes--}}

                        <? $identifier = 'question' . $question->id ?>

                        @foreach($question->choises as $choise)

                            <? $checked = $answer && $answer->wasChecked($choise) ? 'checked="checked"' : null; ?>

                            <li class="checkbox">
                                <label>
                                    <input class="" type="checkbox" {{$checked}} name="{{$identifier}}[]" value="{{ $choise->id }}"/>
                                    {{$choise->title}}
                                </label>
                            </li>

                        @endforeach

                    @else
                        {{--radios--}}

                        <? $identifier = 'question' . $question->id; ?>

                        @foreach($question->choises as $choise)

                            <? $checked = $answer && $answer->wasChecked($choise) ? 'checked="checked"' : null;?>

                            <li class="radio">
                                <label>
                                    <input class="" type="radio" {{$checked}} name="{{$identifier}}" value="{{$choise->id}}"/>
                                    {{$choise->title}}
                                </label>
                            </li>

                        @endforeach

                    @endif

                </ul>

            @endif


            @if($question->explainable == '1')

                <div class="explanation">

                    <textarea name="explanation{{$question->id}}" class="form-control" placeholder="{{Lang::get('instrument.toelichting')}}">{{$answer ? $answer->explanation : null}}</textarea>

                </div>

            @endif

        </div>

    </div>

</div>
