(function ($, app) {

    'use strict';

    $(document).ready(function () {

        $(".actions").on('click', '.remove', function (event) {
            app.confirm(function () {
                var ids = [];

                $('.actions').closest('table').find('tbody td:first-child label input:checked').each(function () {
                    ids.push($(this).val())
                });

                $.ajax({
                    url: '/inschrijvingen/destroy',
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