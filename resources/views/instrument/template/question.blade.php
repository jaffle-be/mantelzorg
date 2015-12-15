<?php

$answer = $survey->getAnswered($question);

$filled = $answer && $answer->wasFilledIn();

$mark = $filled ? 'display:none' : null;

$check = $filled ? null : 'display:none';

/*
 * first part: for mobiles or tablets, only show the first element
 * second part: for anything else then mobiles or tablets show the elements
 */

$tabletOrMobile = UI::isMobile() ||  UI::isTablet();

$show = ($first && $tabletOrMobile) || !$tabletOrMobile ? '' : 'display:none';

?>

<div class="card shadow-z-1 instrument-question question-{{$panel->color}}" data-question-id="{{ $question->id }}" style="{{ $show }}">

    <div class="card-body">

        <div>

            {{--wrapper for borders on desktops--}}
            <div>
                {{--question header--}}
                <div class="header">

                    {{--question status--}}
                    <span class="question-status">

                        <i class="fa fa-question-circle" style="{{ $mark }}"></i>
                        <i class="fa fa-check" style="{{ $check }}"></i>

                    </span>

                    <h3>{{$question->title}}</h3>

                    {{--question editing--}}
                    <span class="question-editing">
                        <i data-show-on="not-editing" class="fa fa-pencil-square-o" style="{{ $first ? 'display:none;' : '' }}"></i>
                        <i data-show-on="editing" data-trigger="toggle-comment" title="{{ Lang::get('questionnaires.meta') }}" class="fa fa-comment" style="{{ $first ? '' : 'display:none;' }}"></i>
                    </span>
                </div>

                {{--question body--}}
                <div class="body" style="{{ $first ? '' : 'display:none' }}">

                    <div class="question"><p>{!! $question->question !!}</p></div>

                    @if($question->meta)

                        <div class="meta alert alert-info" style="display:none">{!! $question->meta !!}</div>

                    @endif

                    {{--multiple choise--}}
                    @if($question->multiple_choise == '1')

                        <div class="form-group">

                            <ul class="choises">

                                @if($question->multiple_answer == '1')
                                    {{--checkboxes--}}

                                    <?php $identifier = 'question'.$question->id ?>

                                    @foreach($question->choises as $choise)

                                        <?php $checked = $answer && $answer->wasChecked($choise) ? 'checked="checked"' : null ?>

                                        <li class="checkbox">
                                            <label class="control-label" id="choise{{$choise->id}}">
                                                <input class="" type="checkbox" {{$checked}} name="{{$identifier}}[]" value="{{ $choise->id }}"/>
                                                {{$choise->title}}
                                            </label>
                                        </li>

                                    @endforeach

                                @else
                                    {{--radios--}}

                                    <?php $identifier = 'question'.$question->id ?>

                                    @foreach($question->choises as $choise)

                                        <?php $checked = $answer && $answer->wasChecked($choise) ? 'checked="checked"' : null?>

                                        <li class="radio">
                                            <label class="control-label" id="choise{{$choise->id}}">
                                                <input class="" type="radio" {{$checked}} name="{{$identifier}}" value="{{$choise->id}}"/>
                                                {{$choise->title}}
                                            </label>
                                        </li>

                                    @endforeach

                                @endif

                            </ul>

                        </div>

                    @endif


                    @if($question->explainable == '1')

                        <div class="form-group">
                            <div class="explanation">

                                <textarea name="explanation{{$question->id}}" class="form-control" placeholder="{{Lang::get('instrument.toelichting')}}">{{$answer ? $answer->explanation : null}}</textarea>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
