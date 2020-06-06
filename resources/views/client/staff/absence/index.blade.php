@extends('backend.layouts.app')

@section('head')
    <title>Absensi | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Absensi
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Absensi</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can('create-staff')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Absensi
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Tambah Absensi</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.staff.absence.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TGL ABSENSI</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='absent_date' value="{{ old('absent_date') }}" required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>STAFF</label>
                        <select class='form-control select2' name='staff_id' required>
                            <option></option>
                            @foreach ($staffs as $staff)
                            <option value='{{ $staff->id }}' {{ old('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }} ({{ optional($staff->position)->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>ABSENSI</label>
                        <select class='form-control select2' name='absence_reason_id' required>
                            <option></option>
                            @foreach ($reasons as $reason)
                            <option value='{{ $reason->id }}' {{ old('absence_reason_id') == $reason->id ? 'selected' : '' }}>{{ $reason->reason }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-12'>
                        <label class='control-label'>KETERANGAN <span class='text-danger'>(Optional)</span></label>
                        <textarea class='form-control' name='note'>{{ old('note') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Kembali</button>
                    <button type="submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @include('inc.datepicker')
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.staff.absence.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'STAFF', data: 'staff.name', name: 'staff.name', defaultContent: '-', class: 'text-center'},
                    {title: 'JABATAN', data: 'staff.position.name', name: 'staff.position.name', defaultContent: '-', class: 'text-center'},
                    {title: 'STATUS', data: 'staff.status', name: 'staff.status', defaultContent: '-', class: 'text-center'},
                   {title: 'DEPARTEMEN', data: 'staff.department.name', name: 'staff.department.name', defaultContent: '-', class: 'text-center'},
                    {title: 'TANGGAL', data: 'absent_date', name: 'absent_date', defaultContent: '-', class: 'text-center'},
                    {title: 'ABSENSI', data: 'absence_reason.reason', name: 'absenceReason.reason', defaultContent: '-', class: 'text-center'},
                    {title: 'KETERANGAN', data: 'note', name: 'note', defaultContent: '-', class: 'text-center'},
                    {title: 'STATUS', data: 'absence_reason.status', name: 'absenceReason.status', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                order: [[0, "desc"]],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                    @if (count($errors) > 0)
                        jQuery("html, body").animate({
                            scrollTop: $('#add-form').offset().top - 100
                        }, "slow");
                    @endif
                }
            });

             $('#add-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#add-form').offset().top - 100
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100
                }, "slow");
            });
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection