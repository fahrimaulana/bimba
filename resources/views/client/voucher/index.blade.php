@extends('backend.layouts.app')

@section('head')
    <title>Daftar Voucher | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Daftar Voucher
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Voucher</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can('create-voucher')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Voucher
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
            <h3>Tambah Voucher Baru</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.voucher.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Kode Voucher</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code') }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='date' value='{{ old('date', now()->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Status</label>
                        <select class='form-control select2' name='status' required>
                            <option></option>
                            <option value='Penyerahan' {{ old('status') == 'Penyerahan' ? 'selected' : '' }}>Penyerahan</option>
                            <option value='Pemakaian' {{ old('status') == 'Pemakaian' ? 'selected' : '' }}>Pemakaian</option>
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Murid (Humas)</label>
                        <select class='form-control select2' name='student_id' required>
                            <option></option>
                            @foreach ($students as $student)
                            <option value='{{ $student->id }}' {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->nim }} ({{ $student->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Murid Baru</label>
                        <select class='form-control select2' name='invited_student_id' required>
                            <option></option>
                            @foreach ($students as $student)
                            <option value='{{ $student->id }}' {{ old('invited_student_id') == $student->id ? 'selected' : '' }}>{{ $student->nim }} ({{ $student->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nilai Voucher</label>
                        <input type='text' class='form-control separator' value='{{ old('value') }}' required>
                        <input type='hidden' name='value' class='separator-hidden' value='{{ old('value') }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Batal</button>
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
    <script src='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}'></script>
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @include('inc.datepicker')

            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });

            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.voucher.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Kode Voucher', data: 'code', name: 'code', defaultContent: '-', class: 'text-center'},
                    {title: 'Tanggal', data: 'date', name: 'date', defaultContent: '-', class: 'text-center'},
                    {title: 'Status', data: 'status', name: 'status', defaultContent: '-', class: 'text-center'},
                    {title: 'Murid (Humas)', data: 'student', name: 'student.nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Murid Baru', data: 'invited_student', name: 'invitedStudent.nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Nilai Voucher', data: 'value', name: 'value', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
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
        });
    </script>
@endsection