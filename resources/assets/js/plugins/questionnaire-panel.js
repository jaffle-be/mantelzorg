(function ($, app) {
    'use strict';

    if (typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator() {
        this.$creator = $('#panel-creator');
        this.$triggers = $(".questionnaires [data-toggle=panel-creator]");
        this.$id = this.$creator.find('#questionnaire-id');
        this.init();
    }

    Creator.prototype = {
        init: function () {
            this.events();
        },
        events: function () {
            var that = this;

            this.$triggers.on('click', function (event) {
                var questionnaire = $(this).closest('.row').data('questionnaire-id');
                that.open(questionnaire);
                event.preventDefault();
            });

            this.$creator.on('click', '.btn-primary', function () {
                that.create();
            });
            this.$creator.on('submit', function(event){
                //prevent submitting to the actual form, or we'd create a questionnaire and see json response
                event.preventDefault();
                that.create();
            });
        },
        create: function () {
            var that = this;
            this.persist(function (response) {
                response.status === 'oke' ? that.success(response) : that.error(response);
            });
        },
        persist: function (callback) {
            var that = this;
            $.ajax({
                url: '/survey/' + that.questionnaire() + '/panel',
                type: 'POST',
                dataType: 'json',
                data: that.data(),
                success: function (response) {
                    callback(response)
                }
            });
        },
        success: function (response) {
            window.location.reload();
        },
        error: function (response) {
            this.$creator.find('.alert-danger').html('');

            for (var i in response.errors)
            {
                this.$creator.find('.alert-danger').append(response.errors[i]).removeClass('hide');
            }
        },
        open: function (id) {
            this.$id.val(id);
            this.$creator.modal('show');
        },
        data: function () {
            return {
                title: this.$creator.find('input[type=text]').val()
            }
        },
        questionnaire: function () {
            return this.$id.val();
        }
    };

    $(document).ready(function () {
        app.questionnaire.panel = new Creator();
    })

})(window.jQuery, window.app);