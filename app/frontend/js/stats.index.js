(function ($, app, Morris, moment) {

    'use strict';

    moment.locale('nl');

    function getDonut(element)
    {
        return Morris.Donut({
            element: element,
            data: [{
                label: 'loading',
                value: ''
            }],
            resize: true
        });
    }

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
                return moment(x).format('d MMMM YYYY');
            }
        });
    }

    function load(stat, chart) {
        $.ajax({
            url: '/stats/ouderen',
            type: 'POST',
            dataType: 'json',
            data: {
                field: stat
            },
            success: function (response) {
                chart.setData(response);
            },
            error: function (response) {
                chart.setData([]);
            }
        });
    }

    function loadSessions(chart)
    {
        $.ajax({
            url: '/stats/sessions',
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

    $(document).ready(function () {

        var woonsituaties = getDonut('woonsituatie'),
            relations = getDonut('mantelzorger_relation'),
            bel_profiel = getDonut('bel_profiel'),
            oorzaken = getDonut('oorzaak_hulpbehoefte');

        load('woonsituatie', woonsituaties);
        load('mantelzorger_relation', relations);
        load('bel_profiel', bel_profiel);
        load('oorzaak_hulpbehoefte', oorzaken);

        var sessions = getLine('sessions');

        loadSessions(sessions);
    });


})(window.jQuery, window.app, window.Morris, window.moment);