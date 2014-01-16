(function($, app)
{
    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator()
    {
        this.$creator = $('#question-creator');
        this.$trigger = $(".question-creator-trigger");
        this.$id = $('#panel-id');
        this.init();
    }



    Creator.prototype = {
        init: function()
        {
            this.events();
        },
        events: function()
        {
            var that = this;

            this.$trigger.on('click', function(event)
            {
                that.open();
                event.preventDefault();
            });
            this.$creator.on('click', '.btn-primary', function()
            {
                that.create();
            });
        },
        create: function()
        {
            var that = this;
            this.persist(function(response)
            {
                response.status === 'oke' ? that.success(response) : that.error(response);
            });
        },
        persist: function(callback)
        {
            var that = this;
            $.ajax({
                url: '/panels/' + that.panel() + '/questions',
                type: 'POST',
                dataType: 'json',
                data: that.data(),
                success: function(response)
                {
                    callback(response)
                }
            });
        },
        success: function(response)
        {
            window.location.reload();
        },
        error: function(response)
        {

        },
        open: function()
        {
            this.$creator.modal('show');
        },
        data: function()
        {
            return {
                title: this.$creator.find('input[name=title]').val(),
                question: this.$creator.find('textarea[name=question]').val(),
                summary_question: this.$creator.find('input[name=summary_question]').prop('checked') ? 1 : 0,
                explainable: this.$creator.find('input[name=explainable]').prop('checked') ? 1 : 0,
                multiple_choise: this.$creator.find('input[name=multiple_choise]').prop('checked') ? 1 : 0
            }
        },
        panel: function()
        {
            return this.$id.val();
        }
    };

    $(document).ready(function()
    {
        app.questionnaire.question = new Creator();
    })

})(window.jQuery, window.app);