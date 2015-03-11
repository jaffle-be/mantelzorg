(function($, app)
{
    'use strict';

    app.fixWidth = function()
    {
        $("body.tablet").css({
            'max-height': $(window).height(),
            'height': $(window).height()
        });

        var contentHeight = $(window).outerHeight() - $("header").outerHeight() - $("#page-header").outerHeight();

        $("body.tablet #content").css({
            'max-height':  contentHeight,
            'min-height': contentHeight,
            height: contentHeight
        });
    };

    $(window).on('resize orientationChanged', function()
    {
        app.fixWidth();
    });

})(window.jQuery, window.app);