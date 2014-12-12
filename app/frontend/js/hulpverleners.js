(function ($, app) {

    'use strict';

    $(document).ready(function () {
        $(".actions").on('click', '.regen-password', function (event) {
            var ids = app.getIds($(this));
            //find the checked boxes
            if (ids.length > 0) {
                var success = function () {
                    //start request
                    $.post('/hulpverleners/regen-passwords', {
                        'ids': ids
                    }, function (response) {
                        window.location.reload();
                    });
                }
                app.confirm(success);
            }

            event.preventDefault();
        }).on('click', '.remove', function (event) {
            var $me = $(this);
            app.confirm(function () {
                var ids = app.getIds($me);

                $.ajax({
                    url: '/hulpverleners/destroy',
                    type: 'POST',
                    dataType: 'json',
                    data: {'ids': ids},
                    success: function () {
                        window.location.reload();
                    }
                });
            });
            event.preventDefault();
        });


    });

})(window.jQuery, window.app);