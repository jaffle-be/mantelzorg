<!-- Modal -->
<div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">{{ Lang::get('master.general.needs-confirmation') }}</h4>
            </div>
            <div class="modal-body">
                <p>
                    @lang('master.general.confirmation-text')
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('master.general.cancel') }}</button>
                <button type="button" class="btn btn-primary btn-confirm">{{ Lang::get('master.general.confirm') }}</button>
            </div>
        </div>
    </div>
</div>