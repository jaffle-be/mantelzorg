(function($, app){

    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};
    if(typeof app.questionnaire === 'undefined') app.questionnaire.question = {};

    function Saver()
    {
        this.$container = $(".questions");
        this.init();
    }

    Saver.prototype = {
        init: function()
        {
            this.panelid = $("#panel-id").val();
            this.events();
        },
        events: function()
        {
            var that = this;
            this.$container.on('change', 'textarea[name=question]', function(){
                that.question($(this));
            });
            this.$container.on('change', '.summary_question', function()
            {
                that.summary_question($(this));
            });
            this.$container.on('change', '.explainable', function()
            {
                that.explainable($(this));
            });
            this.$container.on('change', '.multiple_choise', function()
            {
                that.multiple_choise($(this));
            });
            
        },
        route: function(element, type)
        {
            if(typeof type === 'undefined')
                type = '';
            switch(type)
            {
                case 'choise':
                    return  '/questions/' + element.closest('.question').data('question-id') + '/choises/' + element.closest('li').data('choise-id');
                    break;
                default:
                    return '/panels/' + this.panelid + '/questions/' + element.closest('.question').data('question-id');
                    break;
            }

        },
        question: function(element)
        {
            this.persist(this.route(element), {
                question: element.val()
            });
        },
        summary_question: function(element)
        {
            this.persist(this.route(element), {
                summary_question: element.prop('checked') ? 1 : 0
            });
        },
        explainable: function(element)
        {
            this.persist(this.route(element), {
                explainable: element.prop('checked') ? 1 : 0
            });
        },
        multiple_choise: function(element)
        {
            this.persist(this.route(element), {
                multiple_choise: element.prop('checked') ? 1 : 0
            });
        },
        name: function(element)
        {
            this.persist(this.route(element, 'choise'), {
                title: element.val()
            });
        },
        value: function(element)
        {
            this.persist(this.route(element, 'choise'), {
                name: element.val()
            });
        },
        sort: function(title)
        {

        },
        position: function(weight)
        {
            return weight / 10 + 1;
        },
        persist: function(route, data)
        {
            data = $.extend({
                '_method': 'PUT'
            }, data);

            $.ajax({
                url: route,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function()
                {

                }
            });
        }
    }

    $(document).ready(function()
    {
        app.questionnaire.question.saver = new Saver;
    });

})(window.jQuery, window.app);