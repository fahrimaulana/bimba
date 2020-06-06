<div class="modal fade" id="confirm-rollback-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" role="form" id="confirm-rollback-modal-action">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <input type="hidden" name="id" id="id">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Rollback Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to rollback into this point formula?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Rollback</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#confirm-rollback-modal').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
    $('#confirm-rollback-modal').on('show.bs.modal', function(event){
        $('#confirm-rollback-modal-action').attr('action', $(event.relatedTarget).data('href'));
        $('#id').attr('value', $(event.relatedTarget).data('id'));
    });
</script>