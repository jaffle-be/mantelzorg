
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

})(window.jQuery, window.app);