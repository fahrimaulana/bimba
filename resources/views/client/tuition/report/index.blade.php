@extends('backend.layouts.app')

@section('head')
    <title>Data SPP | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Data SPP
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Data SPP</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <td class="text-success"><b>Sudah Bayar</b></td>
                            <td>Rp {{ thousandSeparator($tuitionStatistic->paid_total) }}</td>
                            <td>{{ $tuitionStatistic->paid_count }} Murid</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <td class="text-danger"><b>Belum Bayar</b></td>
                            <td>Rp {{ thousandSeparator($tuitionStatistic->unpaid_total) }}</td>
                            <td>{{ $tuitionStatistic->unpaid_count }} Murid</td>
                        </tr>
                    </table>
                </div>
            </div>
            @include('inc.success-notif')
            @include('inc.error-list')
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
@endsection

@section('footer')
    <div class="modal fade" id="edit-tuition-note-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('client.tuition.note.update') }}" method="post" role="form" id="confirm-activate-modal-action">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Keterangan SPP</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class='form-control student-field' name='student_id' />
                        <div class="row">
                            <div class='form-group col-md-12'>
                                <label class='control-label'>Keterangan <span class='text-danger'>(Optional)</span></label>
                                <textarea class='form-control note-field' name='note'></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}'></script>
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#edit-tuition-note-modal').on('show.bs.modal', function(event){
                $('#edit-tuition-note-modal .note-field').val($(event.relatedTarget).data('note'));
                $('#edit-tuition-note-modal .student-field').val($(event.relatedTarget).data('student-id'));
            });

            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.tuition.report.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'NIM', data: 'nim', name: 'nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Nama', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Info', data: 'info', name: 'department.name', defaultContent: '-', class: 'text-center'},
                    {title: 'SPP', data: 'fee', name: 'fee', defaultContent: '-', class: 'text-center'},
                    {title: 'Sudah Bayar', data: 'is_paid', name: 'is_paid', defaultContent: '-', class: 'text-center'},
                    {title: 'Keterangan SPP', data: 'note', name: 'note', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                }
            });
        });
    </script>
@endsection