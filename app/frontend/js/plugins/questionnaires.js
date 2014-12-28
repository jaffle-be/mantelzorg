(function ($, app) {

    'use strict';

    if (typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator() {
        this.$creator = $("#questionnaire-creator");
        this.$trigger = $(".page-actions .btn-primary");
        this.init();
    }

    Creator.prototype = {
        init: function () {
            this.events();
        },
        events: function () {
            var that = this;

            this.$trigger.on('click', function () {
                that.$creator.modal('show');
            })

            this.$creator.on('click', '.btn-primary', function (event) {
                that.create();
            });

            this.$creator.on('click', '[data-dismiss="modal"]', function () {
                that.reset()
            });
        },
        create: function () {
            var that = this;
            this.persist(function (response) {
                response.status === 'oke' ? that.success(response) : that.error(response);
            })
        },
        persist: function (callback) {
            var that = this;
            $.ajax({
                url: '/questionnaires',
                type: 'POST',
                data: that.data(),
                dataType: 'json',
                success: function (response) {
                    callback(response);
                }
            })
        },
        data: function () {
            return {
                title: this.$creator.find('input[type=text]').val(),
                active: this.$creator.find('input[type=checkbox]').prop('checked') ? '1' : '0'
            }
        },
        success: function (response) {
            window.location.reload();
        },
        error: function (response) {
            for (var i in response.errors)
            {
                this.$creator.find('.alert-danger').append(response.errors[i]).removeClass('hide');
            }
        },
        reset: function () {
            this.$creator.find('input[type=text]').val('');
            this.$creator.find('input[type=checkbox]').prop('checked', false);
            this.$creator.find('.alert-danger').html('').addClass('hide');
        }
    }

    $(document).ready(function () {
        app.questionnaire.creator = new Creator();
    });

})(window.jQuery, window.app);