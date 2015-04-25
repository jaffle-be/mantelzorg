(function ($, app) {

    'use strict';

    $(document).ready(function () {

        //prevent accidental deletion of files.
        $("[data-trigger='rapport-list']").on('click', 'a[data-trigger="confirm"]', function()
        {
            var link = $(this).attr('href');

            app.confirm(function(){
                window.location = link;
            });

            return false;
        });

    });

})(window.jQuery, window.app);