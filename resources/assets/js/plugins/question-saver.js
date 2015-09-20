(function ($, app) {

    'use strict';

    if (typeof app.questionnaire === 'undefined') app.questionnaire = {};
    if (typeof app.questionnaire === 'undefined') app.questionnaire.question = {};

    function Saver() {
        this.$container = $(".questions");
        this.$right = $(".right");
        this.sortables = '.sortable';
        this.init();
    }

    Saver.prototype = {
        init: function () {
            var that = this;

            this.panelid = $("#panel-id").val();

            this.$container.find(this.sortables).sortable({
                'update': function (event, ui) {
                    that.sort($(this));
                }
            });
            this.events();
        },
        events: function () {
            var that = this;
            this.$container.on('change', 'input[name=title]', function () {
                that.title($(this));
            });
            this.$container.on('change', 'textarea[name=question]', function () {
                that.question($(this));
            });
            this.$container.on('change', '.summary_question', function () {
                that.summary_question($(this));
            });
            this.$container.on('change', '.explainable', function () {
                that.explainable($(this));
            });
            this.$container.on('change', '.multiple_choise', function () {
                that.multiple_choise($(this));
            });
            this.$container.on('change', '.multiple_answer', function () {
                that.multiple_answer($(this));
            });
            this.$container.on('change', '.name', function () {
                that.name($(this));
            });
            this.$container.on('change', '.meta', function () {
                that.meta($(this));
            });
        },
        route: function (element, type) {
            if (typeof type === 'undefined')
                type = '';
            switch (type)
            {
                case 'choise':
                    return '/question/' + element.closest('.question').data('question-id') + '/choise/' + element.closest('li').data('choise-id');
                    break;
                case 'sort':
                    return '/question/' + element.closest('.question').data('question-id') + '/choise/sort';
                    break;
                default:
                    return '/panel/' + this.panelid + '/question/' + element.closest('.question').data('question-id');
                    break;
            }

        },
        title: function (element) {
            this.persist(this.route(element), {
                title: element.val()
            });
        },
        question: function (element) {
            this.persist(this.route(element), {
                question: element.val()
            });
        },
        summary_question: function (element) {
            this.persist(this.route(element), {
                summary_question: element.prop('checked') ? 1 : 0
            });
        },
        explainable: function (element) {
            this.persist(this.route(element), {
                explainable: element.prop('checked') ? 1 : 0
            });
        },
        multiple_choise: function (element) {
            this.persist(this.route(element), {
                multiple_choise: element.prop('checked') ? 1 : 0
            }, function () {
                var box = element.closest('.right').find('.multiple_answer'),
                    answers = box.closest('div.checkbox'),
                    choises = element.closest('.question').find('.choises');

                element.prop('checked') ?
                choises.slideDown() && answers.fadeIn() :
                choises.slideUp() && answers.fadeOut() && box.prop('checked', false);

            });
        },
        multiple_answer: function (element) {
            this.persist(this.route(element), {
                multiple_answer: element.prop('checked') ? 1 : 0
            });
        },
        name: function (element) {
            this.persist(this.route(element, 'choise'), {
                title: element.val()
            });
        },
        meta: function (element) {
            this.persist(this.route(element, 'meta'), {
                meta: element.val()
            });
        },
        sort: function (element) {
            var question = element.closest('.question');
            this.persist(this.route(element, 'sort'), {
                positions: element.closest('.sortable').sortable('toArray')
            }, false, false);
        },
        position: function (weight) {
            return weight / 10 + 1;
        },
        persist: function (route, data, callback, extend) {
            extend = typeof extend === 'undefined' ? true : !!extend;

            if (extend)
            {
                data = $.extend({
                    '_method': 'PUT'
                }, data);
            }

            $.ajax({
                url: route,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function () {
                    if (typeof callback === "function")
                    {
                        callback();
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        app.questionnaire.question.saver = new Saver;
    });

})(window.jQuery, window.app);