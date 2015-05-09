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


        //prevent selecting 2 filters at the same time.
        //this is solely a user enhancement.
        //if one would have javascript disabled, this would not be a problem
        $("#rapport-generator").on('change', 'select', function(){

            var value = $(this).val(),
                clicked = $(this).attr('id');

            if(value == '')
            {
                $("#rapport-generator select").each(function()
                {
                    $(this).attr('disabled', false);
                });
            }
            else{
                $("#rapport-generator select").each(function(){

                    if($(this).attr('id') != clicked && $(this).attr('id') != 'survey')
                    {
                        $(this).val('');
                        $(this).attr('disabled', true);
                    }
                })
            }

        });

    });

})(window.jQuery, window.app);