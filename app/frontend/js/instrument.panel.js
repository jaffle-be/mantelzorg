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
        //on activating a question, we need to show the comment icon instead of the pencil icon
        $el.find('.header [data-show-on="not-editing"]').hide();
        $el.find('.header [data-show-on="editing"]').show();
        $el.find('.body .well').hide();
        $el.find('.body').slideDown();

        if (!$('body').hasClass('tablet'))
        {
            scrollTo($el)
        }
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
        $el.find('.header [data-show-on="not-editing"]').show();
        $el.find('.header [data-show-on="editing"]').hide();
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


    var MobileNavigator = function()
    {
        this.buttons = {
            next: $("[data-trigger='next-question']"),
            previous: $("[data-trigger='previous-question']")
        };
        this.questions = $(".instrument-questions .instrument-question");
        this.current = 1;
        this.events();
    };

    MobileNavigator.prototype = {
        events: function()
        {
            var that = this;

            this.buttons.previous.on('click', function()
            {
                that.previousQuestion();
                return false;
            });
            this.buttons.next.on('click', function()
            {
                that.nextQuestion();
                return false;
            });
        },
        nextQuestion: function()
        {
            var next = this.current + 1;

            if(this.getElementByPosition(next))
            {
                //show or hide the next button
                if(next == this.questions.size())
                {
                    this.buttons.next.hide();
                }
                else
                {
                    this.buttons.next.show();
                }

                this.buttons.previous.show();

                if(this.hideQuestion(this.current) && this.showQuestion(next))
                {
                    this.current++
                }
            }
        },
        previousQuestion: function()
        {
            var previous = this.current - 1;

            if(this.getElementByPosition(previous))
            {
                //show or hide previous button
                if(previous == 1)
                {
                    this.buttons.previous.hide();
                }
                else
                {
                    this.buttons.previous.show();
                }

                this.buttons.next.show();

                if(this.hideQuestion(this.current) && this.showQuestion(previous))
                {
                    this.current--;
                }
            }
        },
        getElementByPosition: function(position)
        {
            return this.questions[position - 1] ? this.questions[position - 1] : false;
        },
        showQuestion: function(position)
        {
            var element = this.getElementByPosition(position);

            if(element)
            {
                $(element).show();

                return true;
            }

            return element;
        },
        hideQuestion: function(position)
        {
            var element = this.getElementByPosition(position);

            if(element)
            {
                $(element).hide();
                return true;
            }

            return false;
        }
    };

    $(document).ready(function () {

        $(".instrument-header").on('click', function () {
            $(this).find('ul').slideToggle();
        });

        $(".instrument-questions").on('click', '.instrument-question .header', function () {
            //if we clicked the current header, hide all questions
            if ($(this).closest('.instrument-question').find('.body').css('display') == 'block')
            {
                toggleQuestions();
            }
            //else we will hide all questions except for the one we clicked
            else
            {
                toggleQuestions($(this).closest('.instrument-question'));
            }
        });

        $(".instrument-questions").on('change', 'input,textarea', function () {
            validateQuestion($(this).closest('.row').find('.instrument-question'));
        });

        $(".instrument-questions").on('click', '[data-trigger="toggle-comment"]', function (event) {
            toggleWell($(this));
            return false;
        });

        $(".instrument-header").on('click', 'a', function (event) {
            $("#next_panel").val($(this).data('target-id'));

            $("#panel-form").submit();

            event.preventDefault();
        });

        var navigator = new MobileNavigator();

    });

})(window.jQuery, window.app)