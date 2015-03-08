(function ($, app) {

    'use strict';

    //toggling of all questions
    function toggleQuestions($show) {
        $(".instrument-question").each(function (i, el) {
            hideQuestion($(el));
        });

        $(':animated').promise().done(function () {
            if ($show)
            {
                showQuestion($show);
            }
        });
    }

    //show a certain question, el is a jquery object
    function showQuestion($el) {
        $el.find(".header .glyphicon-edit").hide();
        $el.find('.header .glyphicon-comment').show();
        $el.find('.body .well').hide();
        $el.find('.body').slideDown();
        scrollTo($el)
    }

    //show/hide a well
    function toggleWell($el) {
        var well = $el.closest('.instrument-question').find('.well');
        if (well.css('display') == 'none')
        {
            well.show();
        }
        else
        {
            well.hide();
        }
    }

    //scroll to a certain element.
    function scrollTo($element) {
        $('html,body').animate({
                scrollTop: $element.offset().top
            },
            'slow');
    }

    //hide a certain question.
    function hideQuestion($el) {
        $el.find(".header .glyphicon-edit").show();
        $el.find('.header .glyphicon-comment').hide();
        $el.find(".body").slideUp();
    }

    //validate if a question has been completed.
    function validateQuestion($el) {
        var $selectables = $el.find("input[type=radio],input[type=checkbox]"),
            $status = $el.closest('.row').find('.question-status'),
            textarea = $el.find('textarea');

        if ($selectables.size() > 0)
        {
            if ($selectables.filter(':checked').size() > 0 || textarea.size() > 0 && textarea.val() != '')
            {
                return okeStatus($status);
            } else
            {
                return notOkeStatus($status);
            }
        } else
        {
            if (textarea.val() == '')
            {
                return notOkeStatus($status);
            } else
            {
                return okeStatus($status);
            }
        }
    }

    //set the question status to not oke
    function notOkeStatus($status) {
        $status.find('.fa-question-circle').show();
        $status.find('.fa-check').hide();
        return false;
    }

    //set the status to oke
    function okeStatus($status) {
        $status.find('.fa-question-circle').hide();
        $status.find('.fa-check').show();
        return true;
    }

    $(document).ready(function () {
        $(".instrument-questions").on('click', '.instrument-question .header', function () {
            if ($(this).closest('.instrument-question').find('.body').css('display') == 'block')
            {
                toggleQuestions();
            } else
            {
                toggleQuestions($(this).closest('.instrument-question'));
            }
        });

        $(".instrument-questions").on('change', 'input,textarea', function () {
            validateQuestion($(this).closest('.row').find('.instrument-question'));
        });

        $(".instrument-questions").on('click', '.glyphicon-comment', function (event) {
            toggleWell($(this));
            return false;
        });

        $(".instrument-header").on('click', 'a', function (event) {
            $("#next_panel").val($(this).data('target-id'));

            $("#panel-form").submit();

            event.preventDefault();
        })

    });

})(window.jQuery, window.app)