(function ($, app) {
    'use strict';

    app.fixWidth = function () {

        $("body.tablet").css({
            'max-height': $(window).height(),
            'height': $(window).height()
        });

        var contentHeight = $(window).height() - $("header").outerHeight() - $("#page-header").outerHeight() - $('.instrument-header').outerHeight();

        $("body.tablet #content").css({
            'max-height': contentHeight,
            'min-height': contentHeight,
            height: contentHeight
        });
    };

    //make sure the container still fits the window after resize or changing tablet orientation.
    $(window).on('resize orientationChanged', function () {
        app.fixWidth();
    });

    app.fixWidth();

    $(window).ready(function () {
        app.fixWidth()
    });


})(window.jQuery, window.app);