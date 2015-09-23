app = {
    datepickers: function () {
        $(".datepicker").datepicker({
            viewMode: 'years',
            format: 'dd/mm/yyyy',
            language: 'nl'
        });
    },
    handlers:{
        confirm: false,
        cancel: false
    },
    booted: {
        confirmation: false,
    },
    confirm: function (confirm, cancel) {
        var modal = $("#confirmation");
        var me = this;

        //make sure to bind handlers only once. they fired all old hooks before.
        if(!me.booted.confirmation){
            modal.on('click', '.btn-confirm', function () {
                me.handlers.confirm();
                modal.modal('hide');
            });

            modal.on('click', '.btn-default', function () {

                if (typeof me.handlers.cancel === 'function')
                {
                    me.handlers.cancel();
                }
            });

            me.booted.confirmation = true;
        }

        //set the handlers to the current confirmation request
        me.handlers.confirm = confirm;
        me.handlers.cancel = cancel;

        //open
        modal.modal();

    }
};