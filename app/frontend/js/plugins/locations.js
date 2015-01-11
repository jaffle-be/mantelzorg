(function ($, app) {

    'use strict';

    $(document).ready(function () {

        if (typeof app.locations === 'undefined') app.locations = {};

        function Creator() {
            this.$modal = $("#locations-creator");
            this.$location = $("#location");
            this.$organisation = $("#organisation");
            this.creating = false;
            this.initialValue = this.$location.val();
            this.init();
        }

        Creator.prototype = {
            init: function () {
                this.events();
            },
            events: function () {
                var that = this;

                that.$location.on('change', function () {
                    if ($(this).val() === 'new')
                    {
                        that.cleanErrors();
                        that.$modal.modal('show');
                    }
                });

                that.$modal.on('click', '.btn-primary', function () {
                    that.creating = true;
                    that.create();
                });

                //override the close -> so we can put the previous value back.
                that.$modal.on('hidden.bs.modal', function () {
                    if (that.creating == false)
                    {
                        that.cancel();
                    }
                });
            },
            create: function () {
                var that = this;
                //if not empty fields
                if (that.validateInput())
                {
                    that.persist(function (response) {
                        that.cleanErrors();
                        response.status === 'oke' ? that.success(response) : that.error(response);
                        that.creating = false;
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
                    success: function (response) {
                        callback(response);
                    }
                });
            },
            cancel: function () {
                var that = this;
                var options = this.$location.find('option').filter(function (item) {
                    return $(this).val() == that.initialValue;
                });
                if(options.size() > 0)
                {
                    this.$location.val(this.initialValue);
                }
                else{
                    this.$location.val(this.$location.find('option:first').val());
                }
            },
            success: function (response) {
                this.$modal.find('.alert').addClass('hide');
                this.add(response.location);
                this.emptyFields();
                this.initialValue = response.location.id
                this.$modal.modal('hide');
            },
            error: function (response) {
                var errors = response.errors;
                for (var field in errors)
                {
                    for (var error in errors[field])
                    {
                        var alert = this.$modal.find('.alert[data-target=' + field + ']');
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
                    html: l.name
                }));
                this.$location.val(l.id);
            },
            cleanErrors: function () {
                this.$modal.find('.alert').html('').addClass('hide');
            },
            validateInput: function () {
                return true;
            }
        };

        function Loader() {
            this.$organisation = $("#organisation");
            this.$location = $("#location");
            this.events();
        }

        Loader.prototype = {
            events: function () {

            },
            load: function (organisationid, setOriginal) {

                if (typeof setOriginal == 'undefined')
                {
                    setOriginal = false;
                }

                if (organisationid)
                {
                    var that = this;
                    $.ajax({
                        url: '/organisations/' + organisationid + '/locations/',
                        dataType: 'json',
                        success: function (response) {
                            that.fill(response, setOriginal);
                        }
                    });
                }
                else
                {
                    this.fill([], setOriginal);
                }
            },
            fill: function (locations, setOriginal) {
                //remove all other options
                var $options = this.$location.find('option'),
                    $emptyOption = $options.filter(':first-child[value=""]');

                //remove all options
                $options.not(':last-child[value="new"], :first-child[value=""]').remove();

                //add new options after the first option ('selecteer een ?'), unless its not there, then use the first element, add that, and use that element to add others after that.
                var teller = 0;
                for (var i in locations)
                {
                    var $newOption = this.getNewOption(locations[i]);
                    if (teller == 0 && $emptyOption.size() == 0)
                    {
                        $emptyOption = $newOption
                        this.$location.prepend($newOption);
                    }
                    else
                    {
                        $emptyOption.after($newOption);
                    }
                }

                if (setOriginal)
                {
                    this.$location.val(this.$location.data('original'));
                }
                else
                {
                    this.$location.val(this.$location.find('option:first').val());
                }

                this.toggle(locations.length > 0);
            },
            getNewOption: function (location) {
                return $('<option>', {
                    value: location.id,
                    html: location.name
                })
            },
            toggle: function (show) {
                if (show)
                {
                    this.$location.parents('.holder').removeClass('hide');
                }
                else
                {
                    this.$location.parents('.holder').addClass('hide');
                }
            }
        };

        app.locations.creator = new Creator();

        app.locations.loader = new Loader();

    });

})(window.jQuery, window.app);