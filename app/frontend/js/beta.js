(function($, _gaq)
{
    'use strict';

    $(document).ready(function()
    {
        var trackers = ['firstname', 'lastname', 'email', 'organisation'],
            form = $("#beta-registration");

        form.on('blur', 'input[type=text]', function(event)
        {
            var el = $(this);
            if($.inArray(el.attr('name') !== -1, trackers) && el.val() !== '')
            {
                _gaq.push(['_trackPageview','/' + el.attr('name')]);
            }
        });

    });

})(window.jQuery, _gaq);