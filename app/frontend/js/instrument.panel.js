(function($, app){

    'use strict';

    //toggling of all questions
    function toggleQuestions($show)
    {
        $(".instrument-question").each(function(i, el){
            hideQuestion($(el));
        });

        $(':animated').promise().done(function()
        {
            if($show)
            {
                showQuestion($show);
            }
        });
    }

    //show a certain question, el is a jquery object
    function showQuestion($el)
    {
        $el.find(".header .glyphicon-edit").hide();
        $el.find('.header .glyphicon-comment').show();
        $el.find('.body').slideDown();
        scrollTo($el)
    }

    //scroll to a certain element.
    function scrollTo($element){
        $('html,body').animate({
                scrollTop: $element.offset().top},
            'slow');
    }

    //hide a certain question.
    function hideQuestion($el)
    {
        $el.find(".header .glyphicon-edit").show();
        $el.find('.header .glyphicon-comment').hide();
        $el.find(".body").slideUp();
    }

    //validate if a question has been completed.
    function validateQuestion($el)
    {
        var $selectables = $el.find("input[type=radio],input[type=checkbox]"),
            $status = $el.closest('.row').find('.question-status');

        if($selectables.size() > 0) {
            if($selectables.filter(':checked').size() == 0) {
                return notOkeStatus($status);
            } else {
                return okeStatus($status);
            }
        } else {
            if($el.find('textarea').val() == '') {
                return notOkeStatus($status);
            } else {
                return okeStatus($status);
            }
        }
    }

    //set the question status to not oke
    function notOkeStatus($status)
    {
        $status.find('.glyphicon-question-sign').show();
        $status.find('.glyphicon-ok').hide();
        return false;
    }

    //set the status to oke
    function okeStatus($status)
    {
        $status.find('.glyphicon-question-sign').hide();
        $status.find('.glyphicon-ok').show();
        return true;
    }

    $(document).ready(function()
    {
        $(".instrument-questions").on('click', '.instrument-question .header', function()
        {
            if($(this).closest('.instrument-question').find('.body').css('display') == 'block') {
                toggleQuestions();
            } else {
                toggleQuestions($(this).closest('.instrument-question'));
            }
        });

        $(".instrument-questions").on('change', 'input,textarea', function()
        {
            validateQuestion($(this).closest('.row').find('.instrument-question'));
        });

        $(".instrument-footer .btn-primary").on('click', function(event)
        {
            var status = true;
            $(".instrument-question").each(function(){
                if(!validateQuestion($(this)))
                {
                    status = false;
                }
            });

            if(!status)
            {
                event.preventDefault();
            }
        });

    });

})(window.jQuery, window.app)