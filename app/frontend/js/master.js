app = {

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
                var options = this.$location.find('option'),
                    size = options.size(),
                    last = options.filter(':last-child');

                options.each(function(i, el){
                    if(i !== 0 && i !== size-1)
                    {
                        el.remove();
                    }
                });

                for(var i in locations)
                {
                    last.before($('<option>', {
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

})(window.jQuery, window.app);