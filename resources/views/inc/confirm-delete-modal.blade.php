<div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" role="form" id="confirm-delete-modal-action">
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Konfirmasi Hapus Data</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapuskan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#confirm-delete-modal').on('show.bs.modal', function(event){
        $('#confirm-delete-modal-action').attr('action', $(event.relatedTarget).data('href'));
    });
</script>