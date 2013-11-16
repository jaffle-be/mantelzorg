(function($, app){

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

})(window.jQuery, window.app);