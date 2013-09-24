(function($){
    'use strict';

    $(document).ready(function()
    {
        $("#log-in").on('click', function(event)
        {
            var form = $(".login-form");

            if(form.css('display') === 'none')
            {
                form.slideDown(300);
            }

            form.find('input:first').focus();


            event.preventDefault();
        });
    });
})(window.jQuery);