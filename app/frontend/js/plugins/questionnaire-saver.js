(function($, app){

    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Saver()
    {
        this.$container = $('.questionnaires');
        this.titles = '.questionnaire-title';
        this.activators = '.icons .header .glyphicon-check, .icons .header .glyphicon-unchecked';
        this.sortables = '.sortable';
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
            });
            this.$container.find(this.sortables).sortable({
                'update': function(event, ui)
                {
                    that.sort($(this));
                }
            });
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
            var that = this,
                activate = box.hasClass('glyphicon-unchecked') ? true : false;
            $.ajax({
                url: '/questionnaires/' + this.questionnaire(box) + '',
                type: 'POST',
                data:{
                    _method: 'PUT',
                    active: activate ? '1':'0'
                },
                dataType: 'json',
                success: function(response)
                {
                    if(activate)
                    {
                        box.closest('.questionnaires').find('.glyphicon-check').each(function()
                        {
                            $(this).addClass('glyphicon-unchecked').removeClass('glyphicon-check');
                        });

                        box.removeClass('glyphicon-unchecked').addClass('glyphicon-check');
                    }
                    else
                    {
                        box.addClass('glyphicon-unchecked').removeClass('glyphicon-check');
                    }
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
        sort: function(panels)
        {
            var questionnaire = this.questionnaire(panels),
                positions = panels.sortable('toArray');

            $.ajax({
                url: '/questionnaires/' + questionnaire + '/panels/sort',
                type: 'POST',
                dataType: 'json',
                data:
                {
                    positions: positions
                },
                success: function(response)
                {

                }
            });

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