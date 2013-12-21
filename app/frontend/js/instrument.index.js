(function($){

    'use strict';

    function persist(value, callback){
        $.ajax({
            url: '/api/mantelzorger/' + value + '/ouderen',
            type: 'GET',
            dataType:'json',
            success: function(response)
            {
                if(response.status === 'oke')
                {
                    callback(true, response.ouderen);
                }
            }
        });
    }

    function toggle(show, ouderen)
    {
        var select = $("#ouderen-select");
        select.html('');
        if(typeof ouderen !== 'undefined')
        {
            for(var i in ouderen)
            {
                var option = ($('<option>', {
                    value: i === 'select' ? '' : i,
                    text: ouderen[i]
                }));

                i === 'select' ? select.prepend(option) : select.append(option);
            }
        }

        show ? select.removeClass('hide') : select.addClass('hide');

    }

    $(document).ready(function()
    {
        $('#mantelzorger-select').on('change', function()
        {
            if($(this).val() !== '')
            {
                persist($(this).val(), toggle)
            }
            else
                toggle(false);
        });

        $("form").on('submit', function(){
            if($("#mantelzorger-select").val() == '' || $("#ouderen-select").val() == '')
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

})(window.jQuery)