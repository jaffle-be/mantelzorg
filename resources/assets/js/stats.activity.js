(function ($, app, Morris, moment) {

    'use strict';

    moment.locale('nl');

    function getLine(element)
    {
        return new Morris.Line({
            // ID of the element in which to draw the chart.
            element: element,
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [],
            // The name of the data record attribute that contains x-values.
            xkey: 'day',
            // A list of names of data record attributes that contain y-values.
            ykeys: ['value'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Sessies'],

            dateFormat: function (x) {
                return moment(x).format('DD/MM/YYYY');
            }
        });
    }

    function getBar(element)
    {
        return new Morris.Bar({
             element: element,

            data: [],
            xkey: 'name',
            ykeys: ['count'],
            labels: ['Sessies'],
        });
    }

    function loadSessions(chart)
    {
        $.ajax({
            url: '/stats/activity/sessions',
            type: 'POST',
            dataType: 'json',
            success: function(response)
            {
                chart.setData(response);
            },
            error: function(){
                chart.setData([]);
            }
        });
    }

    function loadOrganisations(chart)
    {
        $.ajax({
            url: '/stats/activity/organisation-sessions',
            type: 'POST',
            dataType: 'json',
            success: function(response)
            {
                chart.setData(response);
            },
            error: function()
            {
                chart.setData([]);
            }
        })
    }

    $(document).ready(function () {

        var sessions = getLine('sessions');
        var organisationSessions = getBar('organisations');

        loadSessions(sessions);
        loadOrganisations(organisationSessions);
    });


})(window.jQuery, window.app, window.Morris, window.moment);