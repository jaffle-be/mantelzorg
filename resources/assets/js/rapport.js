(function ($, app) {

    'use strict';

    $(document).ready(function () {

        $(".actions").on('click', '.remove', function (event) {
            var $me = $(this);
            app.confirm(function () {
                var ids = app.getIds($me);

                $.ajax({
                    url: '/report/destroy',
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


        //prevent selecting 2 filters at the same time.
        //this is solely a user enhancement.
        //if one would have javascript disabled, this would not be a problem
        $("#rapport-generator").on('change', 'select', function(){

            var value = $(this).val(),
                changed = $(this).attr('id');

            if(value == '')
            {
                $("#rapport-generator select").each(function()
                {
                    $(this).attr('disabled', false);
                });
            }
            else if(changed != 'survey'){
                $("#rapport-generator select").each(function(){

                    if($(this).attr('id') != changed && $(this).attr('id') != 'survey')
                    {
                        $(this).val('');
                        $(this).attr('disabled', true);
                    }
                })
            }

        });

    });

})(window.jQuery, window.app);