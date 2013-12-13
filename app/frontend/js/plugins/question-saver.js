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
            this.events();
        },
        events: function()
        {
            var that = this;
            this.$container.on('change', 'textarea[name=question]', function(){
                that.question($(this));
            });
        },
        route: function(type)
        {
            if(typeof type === 'undefined')
                type = '';
            switch(type)
            {
                case 'choise':
                    return  '/questions/' + element.closest('.question').data('question-id') + '/choises/' + element.closest('li').data('choise-id');
                    break;
                default:
                    return '/questions/' + element.closest('.question').data('question-id')
                    break;
            }

        },
        question: function(element)
        {
            this.persist(this.route(), {
                question: element.val()
            });
        },
        name: function(element)
        {
            this.persist(this.route('choise', element), {
                title: element.val()
            });
        },
        value: function(element)
        {
            this.persist(this.route('choise', element), {
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