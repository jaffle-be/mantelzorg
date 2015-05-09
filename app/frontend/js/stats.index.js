(function ($, app, Morris, Raphael) {

    'use strict';

    var woonsituaties = Morris.Donut({
        element: 'woonsituatie',
        data: [{
            label: 'loading',
            value: ''
        }],
        resize: true
    });

    var relations = Morris.Donut({
        element: 'mantelzorger_relation',
        data: [{
            label: 'loading',
            value: ''
        }],
        resize: true
    });

    var bel_profiel = Morris.Donut({
        element: 'bel_profiel',
        data: [{
            label: 'loading',
            value: ''
        }],
        resize: true
    });

    var oorzaak_hulpbehoefte = Morris.Donut({
        element: 'oorzaak_hulpbehoefte',
        data: [{
            label: 'loading',
            value: ''
        }],
        resize: true
    });

    function load(stat, chart) {
        $.ajax({
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

        load('woonsituatie', woonsituaties);
        load('mantelzorger_relation', relations);
        load('bel_profiel', bel_profiel);
        load('oorzaak_hulpbehoefte', oorzaak_hulpbehoefte);

    });


})(window.jQuery, window.app, window.Morris, window.Raphael);