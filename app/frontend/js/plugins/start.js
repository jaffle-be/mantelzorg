
(function($, app)
{
    $(document).ready()
    {
        app.datepickers();

        $(".actions").on('click', '.select-all', function(event)
        {
            $(this).closest('table').find('tbody tr td:first-child input:checkbox').each(function()
            {
                $(this).prop('checked', true);
            });
            event.preventDefault();
        });

        $(".actions").on('click', '.select-none', function(event)
        {
            $(this).closest('table').find('tbody tr td:first-child input:checkbox').each(function()
            {
                $(this).prop('checked', false);
            });
            event.preventDefault();
        });

    }
})(window.jQuery, window.app);