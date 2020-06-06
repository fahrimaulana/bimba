@extends('backend.layouts.app')

@section('head')
    <title>MBC Murid | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    MBC Murid
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">MBC Murid</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
@endsection

@section('footer')
    <div class="modal show" id="reprint-receipt-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">MBC Murid</h4>
                </div>
                <div class="modal-body pad0" style="height: 500px">
                    @include ('inc.spinner')
                    <embed id="pdf-embed" class="pdf-embed" src="" type="application/pdf" width="100%" height="500px"/>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.student.mbc.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Nim', data: 'nim', name: 'nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Nama', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Kelas', data: 'kelas', name: 'kelas', defaultContent: '-', class: 'text-center'},
                    {title: 'Gol', data: 'group', name: 'group', defaultContent: '-', class: 'text-center'},
                    {title: 'SPP', data: 'payment_method', name: 'payment_method', defaultContent: '-', class: 'text-center'},
                    {title: 'Wali Murid', data: 'parent_name', name: 'parent_name', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                order: [[0, "desc"]],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                }
            });

            $('#reprint-receipt-modal').on('show.bs.modal', function(event) {
                $('#pdf-embed', this).attr('src', $(event.relatedTarget).data('url'));
            });
            $('#reprint-receipt-modal').on('hidden.bs.modal', function () {
                $('#pdf-embed', this).removeAttr('src');
            });
        });
    </script>
@endsection