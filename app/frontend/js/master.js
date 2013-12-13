app = {
    datepickers: function(){
        $(".datepicker").datepicker({
            viewMode: 'years',
            format: 'dd/mm/yyyy',
            language: 'nl'
        });
    }
};
(function($, app)
{

    'use strict';

    $(document).ready(function()
    {

        if(typeof app.organisation === "undefined") app.organisation = {};


        function Creator()
        {
            this.$creator = $("#organisation-creator"),
            this.$organisation = $("#organisation"),
            this.$new = this.$creator.find("[name=organisation_name]");
            this.$locations = $("#location-selector");
            this.init();
        }


        Creator.prototype = {
            init: function () {
                this.events();
            },
            events: function () {
                var that = this;

                this.$creator.on('click', '.btn-primary', function()
                {
                    that.create();
                });

                this.$organisation.on('change', function(){
                    if($(this).val() === 'new')
                    {
                        that.$creator.modal('show', function () {
                            that.$new.focus();
                        });
                    }
                    else if($(this).val() === '')
                    {
                        that.$location.hide();
                        that.$location.val('');
                    }
                    else
                    {
                        app.locations.loader.load($(this).val());
                    }
                });

                this.$creator.on('click', '[data-dismiss="modal"]', function()
                {
                    that.$organisation.val('');
                });

                this.$creator.on('submit', 'form', function(event)
                {
                    that.$creator.find('.btn-primary').trigger('click');
                    event.preventDefault();
                });
            },
            create: function () {
                var that = this;
                if(this.$new.val() !== '')
                {
                    this.persist(function(response){
                        that.cleanErrors();
                        response.status === 'oke' ? that.success(response) : that.error(response);
                    });
                }
            },
            persist: function (callback) {
                $.ajax({
                    url: '/organisations',
                    type: 'POST',
                    data:
                    {
                        name: this.$new.val()
                    },
                    dataType: 'json',
                    success: function(response)
                    {
                        callback(response);
                    }
                });
            },
            success: function (response) {
                this.$creator.find('.alert').addClass('hide');
                this.add(response.organisation);
                this.$new.val('');
                this.$creator.modal('hide');
                app.locations.loader.load(response.organisation.id);
            },
            error: function (response) {
                var errors = response.errors.name;
                for(var i in errors)
                {
                    this.$creator.find('.alert').append($('<span>', {
                        'text' : errors[i]
                    })).removeClass('hide');
                }
            },
            add: function (o) {
                this.$organisation.find('option:last-child').before($('<option>',{
                    value: o.id,
                    html: o.name,
                    selected: true
                }));
            },
            cleanErrors: function () {
                this.$creator.find('.alert').html('');
            }
        };


        $(document).ready(function(){
            app.organisation.creator = new Creator();
        });

    });

})(window.jQuery, window.app);(function($, app){

    'use strict';

    $(document).ready(function(){

        if(typeof app.locations === 'undefined') app.locations = {};

        function Creator() {
            this.$modal = $("#locations-creator");
            this.$location = $("#location");
            this.$organisation = $("#organisation");
            this.init();
        }

        Creator.prototype = {
            init: function () {
                this.events();
            },
            events: function () {
                var that = this;

                that.$location.on('change', function(){
                    if($(this).val() === 'new')
                    {
                        that.cleanErrors();
                        that.$modal.modal('show');
                    }
                });

                that.$modal.on('click', '.btn-primary', function(){
                    that.create();
                });
            },
            create: function() {
                var that = this;
                //if not empty fields
                if(that.validateInput())
                {
                    that.persist(function(response){
                        that.cleanErrors();
                        response.status === 'oke' ? that.success(response) : that.error(response);
                    });
                }
            },
            persist: function (callback) {
                var that = this;
                $.ajax({
                    url: '/organisations/' + that.$organisation.val() + '/locations',
                    type: 'POST',
                    data: {
                        name: this.$modal.find('[name=name]').val(),
                        street: this.$modal.find('[name=street]').val(),
                        postal: this.$modal.find('[name=postal]').val(),
                        city: this.$modal.find('[name=city]').val()
                    },
                    success: function(response) {
                        callback(response);
                    }
                });
            },
            success: function (response) {
                this.$modal.find('.alert').addClass('hide');
                this.add(response.location);
                this.emptyFields();
                this.$modal.modal('hide');
            },
            error: function (response) {
                var errors = response.errors;
                for(var field in errors)
                {
                    for(var error in errors[field])
                    {
                        var alert = this.$modal.find('.alert[data-target='+ field + ']');
                        var html = alert.html();
                        alert.html(html + errors[field][error]);
                        alert.removeClass('hide');
                    }
                }
            },
            emptyFields: function () {
                this.$modal.find('[name=street]').val('');
                this.$modal.find('[name=postal]').val('');
                this.$modal.find('[name=city]').val('');
                this.$modal.find('[name=name]').val('');
            },
            add: function (l) {
                this.$location.find('option:last-child').before($('<option>', {
                    value: l.id,
                    html: l.name,
                    selected: true
                }));
            },
            cleanErrors: function () {
                this.$modal.find('.alert').html('').addClass('hide');
            },
            validateInput: function () {
                return true;
                return this.$modal.find('[name=street]').val() !== ''
                && this.$modal.find('[name=postal]').val() !== ''
                && this.$modal.find('[name=city]').val() !== ''
                && this.$modal.find('[name=name]').val() !== '';
            }

        };

        function Loader ()
        {
            this.$organisation = $("#organisation");
            this.$location = $("#location");
            this.events();
        }

        Loader.prototype = {
            events: function () {

            },
            load: function(organisationid){
                var that = this;
                $.ajax({
                    url: '/organisations/' + organisationid + '/locations/',
                    dataType: 'json',
                    success: function (response)
                    {
                        that.fill(response);
                    }
                });
            },
            fill: function (locations)
            {
                //remove all other options
                var $options = this.$location.find('option'),
                    $new = $options.filter(':first-child[value=]');

                //remove all options
                $options.not(':last-child[value=new], :first-child[value=]').remove();

                //add new options after the first option ('selecteer een ?')
                for(var i in locations)
                {
                    $new.after($('<option>', {
                        value: locations[i].id,
                        html: locations[i].name
                    }));
                }

                this.$location.parents('.holder').removeClass('hide');
            }
        };

        app.locations.creator = new Creator();

        app.locations.loader = new Loader();

    });

})(window.jQuery, window.app);(function($, app){

    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator()
    {
        this.$creator = $("#questionnaire-creator");
        this.$trigger = $(".page-actions .btn-primary");
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

            this.$trigger.on('click', function()
            {
                that.$creator.modal('show');
            })

            this.$creator.on('click', '.btn-primary', function(event)
            {
                that.create();
            });

            this.$creator.on('click', '[data-dismiss="modal"]', function()
            {
                that.reset()
            });
        },
        create: function()
        {
            var that = this;
            this.persist(function(response)
            {
                response.status === 'oke' ? that.success(response) : that.error(response);
            })
        },
        persist: function(callback)
        {
            var that = this;
            $.ajax({
                url: '/questionnaires',
                type: 'POST',
                data: that.data(),
                dataType: 'json',
                success: function(response)
                {
                    callback(response);
                }
            })
        },
        data: function()
        {
            return {
                title: this.$creator.find('input[type=text]').val(),
                active: this.$creator.find('input[type=checkbox]').prop('checked') ? '1' : '0'
            }
        },
        success: function(response)
        {
            window.location.reload();
        },
        error: function(response)
        {
            for(var i in response.errors)
            {
                this.$creator.find('.alert-danger').append(response.errors[i]).removeClass('hide');
            }
        },
        reset: function()
        {
            this.$creator.find('input[type=text]').val('');
            this.$creator.find('input[type=checkbox]').prop('checked', false);
            this.$creator.find('.alert-danger').html('').addClass('hide');
        }
    }

    $(document).ready(function()
    {
        app.questionnaire.creator = new Creator();
    });

})(window.jQuery, window.app);(function($, app)
{
    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator()
    {
        this.$creator = $('#panel-creator');
        this.$triggers = $(".questionnaires [data-toggle=panel-creator]");
        this.$id = this.$creator.find('#questionnaire-id');
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

            this.$triggers.on('click', function(event)
            {
                var questionnaire = $(this).closest('.row').data('questionnaire-id');
                that.open(questionnaire);
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
                url: '/questionnaires/' + that.questionnaire() + '/panels',
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
        open: function(id)
        {
            this.$id.val(id);
            this.$creator.modal('show');
        },
        data: function()
        {
            return {
                title: this.$creator.find('input[type=text]').val()
            }
        },
        questionnaire: function()
        {
            return this.$id.val();
        }
    };

    $(document).ready(function()
    {
        app.questionnaire.panel = new Creator();
    })

})(window.jQuery, window.app);(function($, app){

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

})(window.jQuery, window.app);(function($, app)
{
    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator()
    {
        this.$creator = $('#question-creator');
        this.$trigger = $(".page-actions .btn-primary");
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
                question: this.$creator.find('input[name=question]').val(),
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

})(window.jQuery, window.app);(function($, app)
{
    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator()
    {
        this.$creator = $('#choise-creator');
        this.$trigger = $(".add-choise");
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
                that.open($(this).closest('.question').data('question-id'));
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
                url: '/questions/' + that.questionid + '/choises',
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
        open: function(questionid)
        {
            this.questionid = questionid
            this.$creator.modal('show');
        },
        data: function()
        {
            return {
                title: this.$creator.find('input[name=title]').val(),
                value: this.$creator.find('input[name=value]').val()
            }
        }
    };

    $(document).ready(function()
    {
        app.questionnaire.question = new Creator();
    })

})(window.jQuery, window.app);(function($, app){

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

})(window.jQuery, window.app);/* =========================================================
 * bootstrap-datepicker.js 
 * http://www.eyecon.ro/bootstrap-datepicker
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!function( $ ) {
	
	// Picker object
	
	var Datepicker = function(element, options){
		this.element = $(element);
		this.format = DPGlobal.parseFormat(options.format||this.element.data('date-format')||'mm/dd/yyyy');
		this.picker = $(DPGlobal.template)
							.appendTo('body')
							.on({
								click: $.proxy(this.click, this)//,
								//mousedown: $.proxy(this.mousedown, this)
							});
		this.isInput = this.element.is('input');
		this.component = this.element.is('.date') ? this.element.find('.add-on') : false;
        this.language = options.language;
		
		if (this.isInput) {
			this.element.on({
				focus: $.proxy(this.show, this),
				//blur: $.proxy(this.hide, this),
				keyup: $.proxy(this.update, this)
			});
		} else {
			if (this.component){
				this.component.on('click', $.proxy(this.show, this));
			} else {
				this.element.on('click', $.proxy(this.show, this));
			}
		}
	
		this.minViewMode = options.minViewMode||this.element.data('date-minviewmode')||0;
		if (typeof this.minViewMode === 'string') {
			switch (this.minViewMode) {
				case 'months':
					this.minViewMode = 1;
					break;
				case 'years':
					this.minViewMode = 2;
					break;
				default:
					this.minViewMode = 0;
					break;
			}
		}
		this.viewMode = options.viewMode||this.element.data('date-viewmode')||0;
		if (typeof this.viewMode === 'string') {
			switch (this.viewMode) {
				case 'months':
					this.viewMode = 1;
					break;
				case 'years':
					this.viewMode = 2;
					break;
				default:
					this.viewMode = 0;
					break;
			}
		}
		this.startViewMode = this.viewMode;
		this.weekStart = options.weekStart||this.element.data('date-weekstart')||0;
		this.weekEnd = this.weekStart === 0 ? 6 : this.weekStart - 1;
		this.onRender = options.onRender;
		this.fillDow();
		this.fillMonths();
		this.update();
		this.showMode();
	};
	
	Datepicker.prototype = {
		constructor: Datepicker,
		
		show: function(e) {
			this.picker.show();
			this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
			this.place();
			$(window).on('resize', $.proxy(this.place, this));
			if (e ) {
				e.stopPropagation();
				e.preventDefault();
			}
			if (!this.isInput) {
			}
			var that = this;
			$(document).on('mousedown', function(ev){
				if ($(ev.target).closest('.datepicker').length == 0) {
					that.hide();
				}
			});
			this.element.trigger({
				type: 'show',
				date: this.date
			});
		},
		
		hide: function(){
			this.picker.hide();
			$(window).off('resize', this.place);
			this.viewMode = this.startViewMode;
			this.showMode();
			if (!this.isInput) {
				$(document).off('mousedown', this.hide);
			}
			//this.set();
			this.element.trigger({
				type: 'hide',
				date: this.date
			});
		},
		
		set: function() {
			var formated = DPGlobal.formatDate(this.date, this.format);
			if (!this.isInput) {
				if (this.component){
					this.element.find('input').prop('value', formated);
				}
				this.element.data('date', formated);
			} else {
				this.element.prop('value', formated);
			}
		},
		
		setValue: function(newDate) {
			if (typeof newDate === 'string') {
				this.date = DPGlobal.parseDate(newDate, this.format);
			} else {
				this.date = new Date(newDate);
			}
			this.set();
			this.viewDate = new Date(this.date.getFullYear(), this.date.getMonth(), 1, 0, 0, 0, 0);
			this.fill();
		},
		
		place: function(){
			var offset = this.component ? this.component.offset() : this.element.offset();
			this.picker.css({
				top: offset.top + this.height,
				left: offset.left
			});
		},
		
		update: function(newDate){
			this.date = DPGlobal.parseDate(
				typeof newDate === 'string' ? newDate : (this.isInput ? this.element.prop('value') : this.element.data('date')),
				this.format
			);
			this.viewDate = new Date(this.date.getFullYear(), this.date.getMonth(), 1, 0, 0, 0, 0);
			this.fill();
		},
		
		fillDow: function(){
			var dowCnt = this.weekStart;
			var html = '<tr>';
			while (dowCnt < this.weekStart + 7) {
				html += '<th class="dow">'+DPGlobal.dates[this.language].daysMin[(dowCnt++)%7]+'</th>';
			}
			html += '</tr>';
			this.picker.find('.datepicker-days thead').append(html);
		},
		
		fillMonths: function(){
			var html = '';
			var i = 0
			while (i < 12) {
				html += '<span class="month">'+DPGlobal.dates[this.language].monthsShort[i++]+'</span>';
			}
			this.picker.find('.datepicker-months td').append(html);
		},
		
		fill: function() {
			var d = new Date(this.viewDate),
				year = d.getFullYear(),
				month = d.getMonth(),
				currentDate = this.date.valueOf();
			this.picker.find('.datepicker-days th:eq(1)')
						.text(DPGlobal.dates[this.language].months[month]+' '+year);
			var prevMonth = new Date(year, month-1, 28,0,0,0,0),
				day = DPGlobal.getDaysInMonth(prevMonth.getFullYear(), prevMonth.getMonth());
			prevMonth.setDate(day);
			prevMonth.setDate(day - (prevMonth.getDay() - this.weekStart + 7)%7);
			var nextMonth = new Date(prevMonth);
			nextMonth.setDate(nextMonth.getDate() + 42);
			nextMonth = nextMonth.valueOf();
			var html = [];
			var clsName,
				prevY,
				prevM;
			while(prevMonth.valueOf() < nextMonth) {
				if (prevMonth.getDay() === this.weekStart) {
					html.push('<tr>');
				}
				clsName = this.onRender(prevMonth);
				prevY = prevMonth.getFullYear();
				prevM = prevMonth.getMonth();
				if ((prevM < month &&  prevY === year) ||  prevY < year) {
					clsName += ' old';
				} else if ((prevM > month && prevY === year) || prevY > year) {
					clsName += ' new';
				}
				if (prevMonth.valueOf() === currentDate) {
					clsName += ' active';
				}
				html.push('<td class="day '+clsName+'">'+prevMonth.getDate() + '</td>');
				if (prevMonth.getDay() === this.weekEnd) {
					html.push('</tr>');
				}
				prevMonth.setDate(prevMonth.getDate()+1);
			}
			this.picker.find('.datepicker-days tbody').empty().append(html.join(''));
			var currentYear = this.date.getFullYear();
			
			var months = this.picker.find('.datepicker-months')
						.find('th:eq(1)')
							.text(year)
							.end()
						.find('span').removeClass('active');
			if (currentYear === year) {
				months.eq(this.date.getMonth()).addClass('active');
			}
			
			html = '';
			year = parseInt(year/10, 10) * 10;
			var yearCont = this.picker.find('.datepicker-years')
								.find('th:eq(1)')
									.text(year + '-' + (year + 9))
									.end()
								.find('td');
			year -= 1;
			for (var i = -1; i < 11; i++) {
				html += '<span class="year'+(i === -1 || i === 10 ? ' old' : '')+(currentYear === year ? ' active' : '')+'">'+year+'</span>';
				year += 1;
			}
			yearCont.html(html);
		},
		
		click: function(e) {
			e.stopPropagation();
			e.preventDefault();
			var target = $(e.target).closest('span, td, th');
			if (target.length === 1) {
				switch(target[0].nodeName.toLowerCase()) {
					case 'th':
						switch(target[0].className) {
							case 'switch':
								this.showMode(1);
								break;
							case 'prev':
							case 'next':
								this.viewDate['set'+DPGlobal.modes[this.viewMode].navFnc].call(
									this.viewDate,
									this.viewDate['get'+DPGlobal.modes[this.viewMode].navFnc].call(this.viewDate) + 
									DPGlobal.modes[this.viewMode].navStep * (target[0].className === 'prev' ? -1 : 1)
								);
								this.fill();
								this.set();
								break;
						}
						break;
					case 'span':
						if (target.is('.month')) {
							var month = target.parent().find('span').index(target);
							this.viewDate.setMonth(month);
						} else {
							var year = parseInt(target.text(), 10)||0;
							this.viewDate.setFullYear(year);
						}
						if (this.viewMode !== 0) {
							this.date = new Date(this.viewDate);
							this.element.trigger({
								type: 'changeDate',
								date: this.date,
								viewMode: DPGlobal.modes[this.viewMode].clsName
							});
						}
						this.showMode(-1);
						this.fill();
						this.set();
						break;
					case 'td':
						if (target.is('.day') && !target.is('.disabled')){
							var day = parseInt(target.text(), 10)||1;
							var month = this.viewDate.getMonth();
							if (target.is('.old')) {
								month -= 1;
							} else if (target.is('.new')) {
								month += 1;
							}
							var year = this.viewDate.getFullYear();
							this.date = new Date(year, month, day,0,0,0,0);
							this.viewDate = new Date(year, month, Math.min(28, day),0,0,0,0);
							this.fill();
							this.set();
							this.element.trigger({
								type: 'changeDate',
								date: this.date,
								viewMode: DPGlobal.modes[this.viewMode].clsName
							});
						}
						break;
				}
			}
		},
		
		mousedown: function(e){
			e.stopPropagation();
			e.preventDefault();
		},
		
		showMode: function(dir) {
			if (dir) {
				this.viewMode = Math.max(this.minViewMode, Math.min(2, this.viewMode + dir));
			}
			this.picker.find('>div').hide().filter('.datepicker-'+DPGlobal.modes[this.viewMode].clsName).show();
		}
	};
	
	$.fn.datepicker = function ( option, val ) {
		return this.each(function () {
			var $this = $(this),
				data = $this.data('datepicker'),
				options = typeof option === 'object' && option;
			if (!data) {
				$this.data('datepicker', (data = new Datepicker(this, $.extend({}, $.fn.datepicker.defaults,options))));
			}
			if (typeof option === 'string') data[option](val);
		});
	};

	$.fn.datepicker.defaults = {
		onRender: function(date) {
			return '';
		}
	};
	$.fn.datepicker.Constructor = Datepicker;
	
	var DPGlobal = {
		modes: [
			{
				clsName: 'days',
				navFnc: 'Month',
				navStep: 1
			},
			{
				clsName: 'months',
				navFnc: 'FullYear',
				navStep: 1
			},
			{
				clsName: 'years',
				navFnc: 'FullYear',
				navStep: 10
		}],
		dates:{
            en:{
                days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
                months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            },
            nl:{
                days: ["Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag", "Zondag"],
                daysShort: ["Zon", "Maa", "Din", "Woe", "Don", "Vri", "Zat", "Zon"],
                daysMin: ["Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za", "Zo"],
                months: ["Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"],
                monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"]
            }
		},
		isLeapYear: function (year) {
			return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0))
		},
		getDaysInMonth: function (year, month) {
			return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]
		},
		parseFormat: function(format){
			var separator = format.match(/[.\/\-\s].*?/),
				parts = format.split(/\W+/);
			if (!separator || !parts || parts.length === 0){
				throw new Error("Invalid date format.");
			}
			return {separator: separator, parts: parts};
		},
		parseDate: function(date, format) {
			var parts = date.split(format.separator),
				date = new Date(),
				val;
			date.setHours(0);
			date.setMinutes(0);
			date.setSeconds(0);
			date.setMilliseconds(0);
			if (parts.length === format.parts.length) {
				var year = date.getFullYear(), day = date.getDate(), month = date.getMonth();
				for (var i=0, cnt = format.parts.length; i < cnt; i++) {
					val = parseInt(parts[i], 10)||1;
					switch(format.parts[i]) {
						case 'dd':
						case 'd':
							day = val;
							date.setDate(val);
							break;
						case 'mm':
						case 'm':
							month = val - 1;
							date.setMonth(val - 1);
							break;
						case 'yy':
							year = 2000 + val;
							date.setFullYear(2000 + val);
							break;
						case 'yyyy':
							year = val;
							date.setFullYear(val);
							break;
					}
				}
				date = new Date(year, month, day, 0 ,0 ,0);
			}
			return date;
		},
		formatDate: function(date, format){
			var val = {
				d: date.getDate(),
				m: date.getMonth() + 1,
				yy: date.getFullYear().toString().substring(2),
				yyyy: date.getFullYear()
			};
			val.dd = (val.d < 10 ? '0' : '') + val.d;
			val.mm = (val.m < 10 ? '0' : '') + val.m;
			var date = [];
			for (var i=0, cnt = format.parts.length; i < cnt; i++) {
				date.push(val[format.parts[i]]);
			}
			return date.join(format.separator);
		},
		headTemplate: '<thead>'+
							'<tr>'+
								'<th class="prev">&lsaquo;</th>'+
								'<th colspan="5" class="switch"></th>'+
								'<th class="next">&rsaquo;</th>'+
							'</tr>'+
						'</thead>',
		contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>'
	};
	DPGlobal.template = '<div class="datepicker dropdown-menu">'+
							'<div class="datepicker-days">'+
								'<table class=" table-condensed">'+
									DPGlobal.headTemplate+
									'<tbody></tbody>'+
								'</table>'+
							'</div>'+
							'<div class="datepicker-months">'+
								'<table class="table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
								'</table>'+
							'</div>'+
							'<div class="datepicker-years">'+
								'<table class="table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
								'</table>'+
							'</div>'+
						'</div>';

}( window.jQuery );
(function($, app)
{
    $(document).ready()
    {
        app.datepickers();
    }
})(window.jQuery, window.app);