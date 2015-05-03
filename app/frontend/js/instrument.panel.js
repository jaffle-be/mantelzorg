(function ($, app) {
    'use strict';

    var RegularNavigator = function () {
        this.wrapper = $(".instrument-questions");
        this.questions = $(".instrument-question");
        this.listQuestions = $(".question-list")
        //are we on tablet or mobile?
        this.isMobileOrTablet = $("body").hasClass('tablet');

        if(this.isMobileOrTablet)
        {
            this.initLayout();
        }

        this.events();
    };

    RegularNavigator.prototype = {
        //adjustments for layout on phones or tablets should be done here
        initLayout: function()
        {
            //show all question bodies, no need for effects
            this.questions.find('.body').show();
            this.questions.find('[data-show-on="editing"]').show();
            this.questions.find('[data-show-on="not-editing"]').hide();
        },
        events: function () {
            var that = this;

            if(!this.isMobileOrTablet)
            {
                this.wrapper.on('click', '.instrument-question .header', function () {
                    //if we clicked the current header, hide all questions
                    that.handleHeaderClick(this);
                });
            }

            this.wrapper.on('change', 'input,textarea', function () {
                that.validateQuestion($(this).closest('.instrument-question'));
            });

            this.wrapper.on('click', '[data-trigger="toggle-comment"]', function (event) {
                that.toggleWell($(this));
                return false;
            });
        },
        handleHeaderClick: function (clicked) {
            if ($(clicked).closest('.instrument-question').find('.body').css('display') == 'block')
            {
                this.toggleQuestions();
            }
            //else we will hide all questions except for the one we clicked
            else
            {
                this.toggleQuestions($(clicked).closest('.instrument-question'));
            }
        },
        toggleQuestions: function ($show) {
            var that = this;

            this.questions.each(function (i, el) {
                that.hideQuestion($(el));
            });

            $(':animated').promise().done(function () {
                if ($show)
                {
                    that.showQuestion($show);
                }
            });
        },
        showQuestion: function ($el) {
            this.startEditing($el);

            $el.find('.body .well').hide();
            $el.find('.body').slideDown();

            if (!$('body').hasClass('tablet'))
            {
                this.scrollTo($el)
            }
        },
        startEditing: function($el){
            $el.find('.header [data-show-on="not-editing"]').hide();
            $el.find('.header [data-show-on="editing"]').show();
        },
        toggleWell: function ($el) {
            var well = $el.closest('.instrument-question').find('.well');

            well.css('display') == 'none' ? well.show() : well.hide();
        },
        scrollTo: function ($element) {
            $('html,body').animate({
                    scrollTop: $element.offset().top
                },
                'slow');
        },
        hideQuestion: function ($el) {
            $el.find('.header [data-show-on="not-editing"]').show();
            $el.find('.header [data-show-on="editing"]').hide();
            $el.find(".body").slideUp();
        },
        validateQuestion: function ($el) {
            //checkboxes or radios
            var $selectables = $el.find("input[type=radio],input[type=checkbox]"),
            //status icon wrapper
                $status = $el.closest('.instrument-question').find('.question-status'),
                $listStatus = this.listQuestions.find('[data-question-id=' + $el.data('question-id') + ']'),
                textarea = $el.find('textarea'),
                status = this.somethingWasChecked($selectables) || this.somethingWasFilledIn(textarea);

            this.questionStatus($status, status);
            this.questionStatus($listStatus, status);
        },
        somethingWasChecked: function ($selectables) {
            return $selectables.size() > 0 && $selectables.filter(':checked').size() > 0
        },
        somethingWasFilledIn: function (textarea) {
            return textarea.size() > 0 && textarea.val() != '';
        },
        questionStatus: function ($status, status) {
            if (status)
            {
                $status.find('.fa-question-circle').hide();
                $status.find('.fa-check').show();
            }
            else
            {
                $status.find('.fa-question-circle').show();
                $status.find('.fa-check').hide();
            }
        }
    };


    var MobileNavigator = function () {
        this.buttons = {
            next: $("[data-trigger='next-question']"),
            previous: $("[data-trigger='previous-question']"),
            confirm: $("[data-trigger='confirm']")
        };
        this.content = $('#content');
        //the actual questions
        this.questions = $(".instrument-questions .instrument-question");
        //navigation list of questions in the footer
        this.listQuestions = $(".instrument-footer .question-list ul li");
        this.trigger = $(".instrument-footer h4");
        this.switcher = $(".instrument-questions .question-list ul");
        this.title = $(".instrument-footer h4 .title");
        this.current = 1;
        this.events();
    };

    MobileNavigator.prototype = {
        events: function () {
            var that = this;

            this.buttons.previous.on('click', function () {
                that.previousQuestion();
                return false;
            });
            this.buttons.next.on('click', function () {
                that.nextQuestion();
                return false;
            });
            this.trigger.on('click', function(event)
            {
                that.showSwitcher();
                event.stopPropagation();
            });

            this.switcher.on('click', 'li', function()
            {
                that.goto(this);
            });

            $("html").on('click', function(event)
            {
                that.hideSwitcher();
            });
        },
        showSwitcher: function()
        {
            this.switcher.show();
        },
        hideSwitcher: function()
        {
            this.switcher.hide();
        },
        resetView: function ()
        {
            this.content.scrollTop(0);
        },
        goto: function(clicked)
        {
            var target = $(clicked).data('target-position');

            if(!isNaN(target) && 0 < target && target <= this.questions.size())
            {
                this.buttons.previous.toggle(target != 1);
                this.buttons.next.toggle(target != this.questions.size());

                this.hideQuestion(this.current);
                this.showQuestion(target);

                this.current = target;
            }
        },
        nextQuestion: function () {
            var next = this.current + 1;

            if (this.getQuestion(next))
            {
                //show or hide the next button
                if (next == this.questions.size())
                {
                    this.buttons.next.hide();
                }
                else
                {
                    this.buttons.next.show();
                }

                this.buttons.previous.show();

                if (this.hideQuestion(this.current) && this.showQuestion(next))
                {
                    this.current++
                }
            }
        },
        previousQuestion: function () {
            var previous = this.current - 1;

            if (this.getQuestion(previous))
            {
                //show or hide previous button
                if (previous == 1)
                {
                    this.buttons.previous.hide();
                }
                else
                {
                    this.buttons.previous.show();
                }

                this.buttons.next.show();

                if (this.hideQuestion(this.current) && this.showQuestion(previous))
                {
                    this.current--;
                }
            }
        },
        getQuestion: function (position) {
            return this.questions[position - 1] ? this.questions[position - 1] : false;
        },
        getListItem: function(position)
        {
            return this.listQuestions[position - 1] ? this.listQuestions[position - 1] : false;
        },
        showQuestion: function (position) {
            var element = this.getQuestion(position);
            var listItem = this.getListItem(position);

            if (element)
            {
                $(element).show();
                this.resetView();

                if(listItem)
                {
                    $(listItem).addClass('active');
                    this.title.html($(listItem).find('[data-title]').html());
                }

                //if the question is the last question, we show the confirm button
                if(position == this.questions.size())
                {
                    this.buttons.confirm.show();
                }
                else{
                    this.buttons.confirm.hide();
                }
            }

            return element ? true : false;
        },
        hideQuestion: function (position) {
            var element = this.getQuestion(position);
            var listItem = this.getListItem(position);

            if (element)
            {
                $(element).hide();

                if(listItem)
                {
                    $(listItem).removeClass('active');
                }

                return true;
            }

            return false;
        }
    };

    $(document).ready(function () {

        $(".instrument-header").on('click', function () {
            $(this).find('ul').slideToggle();
        });

        $(".instrument-header").on('click', 'a', function (event) {
            $("#next_panel").val($(this).data('target-id'));

            $("#panel-form").submit();

            event.preventDefault();
        });

        var regular = new RegularNavigator();

        var mobile = new MobileNavigator();

    });

})(window.jQuery, window.app)