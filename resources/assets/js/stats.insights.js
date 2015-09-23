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

    function load(stat, chart) {
        $.ajax({
            url: '/stats/insights/ouderen',
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


    $(document).ready(function () {

        var woonsituaties = getDonut('woonsituatie'),
            relations = getDonut('mantelzorger_relation'),
            bel_profiel = getDonut('bel_profiel'),
            oorzaken = getDonut('oorzaak_hulpbehoefte');

        load('woonsituatie', woonsituaties);
        load('mantelzorger_relation', relations);
        load('bel_profiel', bel_profiel);
        load('oorzaak_hulpbehoefte', oorzaken);

    });


})(window.jQuery, window.app, window.Morris, window.moment);