(function ($, app) {
    'use strict';

    if (typeof app.questionnaire === 'undefined') app.questionnaire = {};

    function Creator() {
        this.$creator = $('#choise-creator');
        this.$trigger = $(".add-choise");
        this.init();
    }

    Creator.prototype = {
        init: function () {
            this.events();
        },
        events: function () {
            var that = this;

            this.$creator.on('submit', function(event){
                event.preventDefault();
                that.create();
            });

            this.$trigger.on('click', function (event) {
                that.open($(this).closest('.question').data('question-id'));
                event.preventDefault();
            });
            this.$creator.on('click', '.btn-primary', function () {
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
                url: '/questions/' + that.questionid + '/choises',
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

        },
        open: function (questionid) {
            this.questionid = questionid
            this.$creator.modal('show');
        },
        data: function () {
            return {
                title: this.$creator.find('input[name=title]').val()
            }
        }
    };

    $(document).ready(function () {
        app.questionnaire.question = new Creator();
    })

})(window.jQuery, window.app);