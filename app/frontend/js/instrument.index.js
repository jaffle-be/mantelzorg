(function ($, app) {

    var mantelzorger,
        oudere;

    'use strict';

    function persist(value, callback) {
        $.ajax({
            url: '/api/mantelzorger/' + value + '/ouderen',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'oke')
                {
                    callback(true, response.ouderen);
                }
            }
        });
    }

    function toggle(show, ouderen) {
        oudere.html('');
        if (typeof ouderen !== 'undefined')
        {
            for (var i in ouderen)
            {
                var option = ($('<option>', {
                    value: i === 'select' ? '' : i,
                    text: ouderen[i]
                }));

                i === 'select' ? oudere.prepend(option) : oudere.append(option);
            }
        }

        show ? oudere.removeClass('hide') : oudere.addClass('hide');

    }

    function doExport(){

        $(".actions").on('click', '.export', function(event){

            var me = $(this);

            ids = app.getIds(me);

            if(ids.length > 0)
            {
                var idString = '';

                for(var i = 0; i < ids.length; i++)
                {
                    idString += '&ids[]=' + ids[i];
                }

                window.location.href = "/instrument/export?" + idString;
            }

            event.preventDefault();
        });

    }

    function purge() {

        $('.actions').on('click', '.remove', function (event) {
            var me = $(this);

            app.confirm(function () {

                var ids = app.getIds(me);

                $.ajax({
                    url: '/instrument/destroy',
                    type: 'POST',
                    dataType: 'json',
                    data: {'ids': ids},
                    success: function () {
                        window.location.reload();
                    }
                });
            });
            event.preventDefault();
        });
    }

    $(document).ready(function () {
        //allow purging
        purge();
        doExport();


        mantelzorger = $("#mantelzorger-select");
        oudere = $("#ouderen-select");

        if (mantelzorger.val() != '')
        {
            persist(mantelzorger.val(), toggle)
        }

        mantelzorger.on('change', function () {
            if ($(this).val() !== '')
            {
                persist($(this).val(), toggle)
            }
            else
                toggle(false);
        });

        $("#creator-form").on('submit', function () {
            if (mantelzorger.val() == '' || oudere.val() == '' || oudere.val() == undefined)
            {
                $(".alert").show();
                return false;
            }
            else
            {
                $(".alert").hide();
            }
        });

    });

})(window.jQuery, window.app);