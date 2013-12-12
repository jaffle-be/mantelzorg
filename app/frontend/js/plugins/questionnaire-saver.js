(function($, app){

    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Saver()
    {
        this.$container = $('.questionnaires');
        this.titles = '.questionnaire-title';
        this.activators = '.icons .header .glyphicon-check, .icons .header .glyphicon-unchecked';
        this.panelTitles = '.questionnaire-panel-title';
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

            //pass all elements as a jquery element as a convenience.
            this.$container.on('change', this.titles, function()
            {
                that.title($(this));
            });

            this.$container.on('change', this.panelTitles, function(){
                that.panelTitle($(this));
            });

            this.$container.on('click', this.activators, function()
            {
                that.activation($(this));
            })
        },
        title: function(title)
        {
            $.ajax({
                url: '/questionnaires/' + this.questionnaire(title),
                type: 'POST',
                data: {
                    title: title.val(),
                    _method: 'PUT'
                },
                dataType: 'json',
                success: function(response)
                {
                    if(response.status === 'oke')
                    {
                        title.closest('.row').find('.icons .body i').removeClass('fade');
                        setTimeout(function()
                        {
                            title.closest('.row').find('.icons .body i').addClass('fade');
                        }, 3000);
                    }
                }
            });
        },
        activation: function(box)
        {
            var that = this;
            $.ajax({
                url: '/questionnaires/' + this.questionnaire(box) + '',
                type: 'POST',
                data:{
                    _method: 'PUT',
                    active: box.hasClass('glyphicon-unchecked') ? '1':'0'
                },
                dataType: 'json',
                success: function()
                {

                }
            });
        },
        panelTitle: function(title)
        {
            var that = this;
            $.ajax({
                url: '/questionnaires/' + this.questionnaire(title) + '/panels/' + this.panel(title),
                type: 'POST',
                data: {
                    title: title.val(),
                    _method: 'PUT'
                },
                dataType: 'json',
                success: function(response)
                {
                    var position = that.position(title.data('panel-weight'));
                    if(response.status === 'oke')
                    {
                        title.closest('.row').find('.savers .body ul li:nth-child('+ position + ') i').removeClass('fade');
                        setTimeout(function()
                        {
                            title.closest('.row').find('.savers .body ul li:nth-child('+ position + ') i').addClass('fade');
                        }, 3000);
                    }
                }
            })
        },
        position: function(weight)
        {
            return weight / 10 + 1;
        },
        questionnaire: function(element)
        {
            return element.closest('.row').data('questionnaire-id');
        },
        panel: function(element)
        {
            return element.closest('li').data('questionnaire-panel-id');
        }
    }

    $(document).ready(function()
    {
        app.questionnaire.saver = new Saver;
    });

})(window.jQuery, window.app);