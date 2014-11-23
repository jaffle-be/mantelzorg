app = {
    datepickers: function () {
        $(".datepicker").datepicker({
            viewMode: 'years',
            format: 'dd/mm/yyyy',
            language: 'nl'
        });
    },
    confirm: function (success, cancel) {
        var modal = $("#confirmation");

        modal.modal();

        modal.on('click', '.btn-confirm', function () {
            success();
            modal.modal('hide');
        })

        modal.on('click', '.btn-default', function () {

            if(typeof cancel === 'function')
            {
                cancel();
            }
        });
    }
};