(function ($, app) {

    'use strict';

    $(document).ready(function () {

        $(".actions").on('click', '.regen-password', function (event) {

            var ids = [];

            //find the checked boxes
            $(this).closest('table').find('tbody tr td:first-child input:checkbox').each(function () {

                if($(this).prop('checked') == true)
                {
                    //add id to ids if it was checked
                    ids.push($(this).closest('tr').data('id'));
                }

            });

            //start request
            $.post('/hulpverleners/regen-passwords', {
                'ids':ids
            }, function(response)
            {

            });

            event.preventDefault();

        });

    });

})(window.jQuery, window.app);