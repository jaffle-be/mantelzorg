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

    function load() {
        $.ajax({
            url: '/stats/insights/ouderen',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                setData(response);
            },
        });
    }

    function setData(response)
    {
        var chars = ['mantelzorger_relation', 'ouderen_woonsituatie', 'oorzaak_hulpbehoefte', 'bel_profiel'];

        _.each(chars, function(item){
            getDonut(item).setData(response[item].buckets);
        });
    }

    $(document).ready(function () {

        load();

    });


})(window.jQuery, window.app, window.Morris, window.moment);