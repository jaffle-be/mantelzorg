(function ($, app, Morris, Raphael) {

    'use strict';

    var OuderenChart = function()
    {
        this.woonsituaties = Morris.Donut({
            element: 'woonsituatie',
            data: [{
                label: 'loading',
                value: ''
            }],
            resize: true
        });

        this.relations = Morris.Donut({
            element: 'mantelzorger_relation',
            data: [{
                label: 'loading',
                value: ''
            }],
            resize: true
        });

        this.bel_profiel = Morris.Donut({
            element: 'bel_profiel',
            data: [{
                label: 'loading',
                value: ''
            }],
            resize: true
        });

        this.oorzaak_hulpbehoefte = Morris.Donut({
            element: 'oorzaak_hulpbehoefte',
            data: [{
                label: 'loading',
                value: ''
            }],
            resize: true
        });
    };

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

        var ouderen = new OuderenChart();

        load('woonsituatie', ouderen.woonsituaties);
        load('mantelzorger_relation', ouderen.relations);
        load('bel_profiel', ouderen.bel_profiel);
        load('oorzaak_hulpbehoefte', ouderen.oorzaak_hulpbehoefte);

    });


})(window.jQuery, window.app, window.Morris, window.Raphael);