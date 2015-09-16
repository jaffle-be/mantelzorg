(function ($, app) {

    'use strict';

    if (typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Saver() {
        this.$container = $('.questionnaires');
        this.titles = '.questionnaire-title';
        this.activators = '.icons .header [data-trigger="deactivate"], .icons .header [data-trigger="activate"]';
        this.sortables = '.sortable';
        this.panelTitles = '.questionnaire-panel-title';
        this.colorSelectors = '.dropdown-menu .panel-color';
        this.colors = ['blue', 'red', 'gray', 'yellow', 'green', 'orange', 'purple'];
        this.init();
    }

    Saver.prototype = {
        init: function () {
            var that = this;
            this.events();
            this.$container.find(this.sortables).sortable({
                'handle': '.input-group-addon',
                'update': function (event, ui) {
                    that.sort($(this));
                }
            });
        },
        events: function () {
            var that = this;

            //pass all elements as a jquery element as a convenience.
            this.$container.on('change', this.titles, function () {
                that.title($(this));
            });

            this.$container.on('change', this.panelTitles, function () {
                that.panelTitle($(this));
            });

            this.$container.on('click', this.activators, function () {
                that.activation($(this));
            });

            this.$container.on('click', this.colorSelectors, function (event) {
                that.color($(this));
                event.preventDefault();
            });
        },
        title: function (title) {
            $.ajax({
                url: '/survey/' + this.questionnaire(title),
                type: 'POST',
                data: {
                    title: title.val(),
                    _method: 'PUT'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'oke')
                    {
                        title.closest('.row').find('.icons .body i').removeClass('fade');
                        setTimeout(function () {
                            title.closest('.row').find('.icons .body i').addClass('fade');
                        }, 3000);
                    }
                }
            });
        },
        activation: function (box) {
            var that = this,
                activate = box.data('trigger') == 'activate' ? true : false;
            $.ajax({
                url: '/survey/' + this.questionnaire(box) + '',
                type: 'POST',
                data: {
                    _method: 'PUT',
                    active: activate ? '1' : '0'
                },
                dataType: 'json',
                success: function (response) {
                    var questionnaires = box.closest('.questionnaires');
                    var questionnaire = box.closest('.header');

                    //set all questionnaires to 'deactivated state'
                    questionnaires.find('[data-trigger="activate"]').show();
                    questionnaires.find('[data-trigger="deactivate"]').hide();

                    if(activate)
                    {
                        questionnaire.find('[data-trigger="activate"]').hide();
                        questionnaire.find('[data-trigger="deactivate"]').show();
                    }
                    else{
                        questionnaire.find('[data-trigger="activate"]').show();
                        questionnaire.find('[data-trigger="deactivate"]').hide();
                    }
                }
            });
        },
        panelTitle: function (title) {
            var that = this;
            $.ajax({
                url: '/survey/' + this.questionnaire(title) + '/panel/' + this.panel(title),
                type: 'POST',
                data: {
                    title: title.val(),
                    _method: 'PUT'
                },
                dataType: 'json',
                success: function (response) {
                    var position = that.position(title.data('panel-weight'));
                    if (response.status === 'oke')
                    {
                        title.closest('.row').find('.savers .body ul li:nth-child(' + position + ') i').removeClass('fade');
                        setTimeout(function () {
                            title.closest('.row').find('.savers .body ul li:nth-child(' + position + ') i').addClass('fade');
                        }, 3000);
                    }
                }
            })
        },
        sort: function (panels) {
            var questionnaire = this.questionnaire(panels),
                positions = panels.sortable('toArray');

            $.ajax({
                url: '/survey/' + questionnaire + '/panel/sort',
                type: 'POST',
                dataType: 'json',
                data: {
                    positions: positions
                },
                success: function (response) {

                }
            });

        },
        color: function (element) {
            var that = this,
                original = element.parents('.colors').find('.dropdown-toggle .panel-color'),
                color = this.currentColor(element);

            $.ajax({
                type: 'POST',
                url: '/survey/' + this.questionnaire(element) + '/panel/' + this.panel(element),
                data: {
                    _method: 'PUT',
                    color: color
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'oke')
                    {
                        that.changeColor(original, that.currentColor(original), color);
                    }
                }
            });


        },
        currentColor: function (element) {
            for (var i in this.colors)
            {
                if (element.hasClass('panel-' + this.colors[i]))
                    return this.colors[i];
            }
        },
        changeColor: function (element, original, color) {
            element.removeClass('panel-' + original).addClass('panel-' + color);
        },
        position: function (weight) {
            return weight / 10 + 1;
        },
        questionnaire: function (element) {
            return element.closest('.row').data('questionnaire-id');
        },
        panel: function (element) {
            return element.closest('li[data-questionnaire-panel-id]').data('questionnaire-panel-id');
        }
    }

    $(document).ready(function () {
        app.questionnaire.saver = new Saver;
    });

})(window.jQuery, window.app);