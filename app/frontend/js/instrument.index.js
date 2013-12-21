(function($){

    var mantelzorger,
        oudere;

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
        oudere.html('');
        if(typeof ouderen !== 'undefined')
        {
            for(var i in ouderen)
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

    $(document).ready(function()
    {
        mantelzorger = $("#mantelzorger-select");
        oudere = $("#ouderen-select");

        if(mantelzorger.val() != '')
        {
            persist(mantelzorger.val(), toggle)
        }

        mantelzorger.on('change', function()
        {
            if($(this).val() !== '')
            {
                persist($(this).val(), toggle)
            }
            else
                toggle(false);
        });

        $("form").on('submit', function(){
            if(mantelzorger.val() == '' || oudere.val() == '' || oudere.val() == undefined)
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