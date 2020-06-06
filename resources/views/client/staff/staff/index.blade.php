@extends('backend.layouts.app')

@section('head')
    <title>Daftar Staff | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Daftar Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Staff</li>
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
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Staff
                        </a>
                        <div class="pull-right">
                            <div class="col-xs-7">
                                <a href="{{ url('../storage/app/uploads/staff/fail/import-staff-fail.csv') }}" class="btn btn-danger btn-responsive "><i class="icon wb-download"></i> Daftar Staff Gagal Import</a>
                            </div>
                            <div class="col-xs-5">
                                <button type="button" class="btn btn-info btn-responsive" data-toggle="modal" data-target="#modal-import-customer"><i class="icon wb-download"></i> Import Staff</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Tambah Staff</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.staff.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIK</label>
                        <input type='text' name='nik' class='form-control' value='{{ old('nik') }}'/>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value="{{ old('name') }}" required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>JABATAN</label>
                        <select class='form-control select2' name='position_id' required>
                            <option></option>
                            @foreach ($staffPositions as $staffPosition)
                            <option value='{{ $staffPosition->id }}' {{ old('position_id') == $staffPosition->id ? 'selected' : '' }}>{{ $staffPosition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>STATUS</label>
                        <select class='form-control select2' name='status' required>
                            <option></option>
                            <option value="Active" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Intern" {{ old('status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                            <option value="Resign" {{ old('status') == 'Resign' ? 'selected' : '' }}>Resign</option>
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>DEPARTEMEN</label>
                        <select class='form-control select2' name='department_id' required>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL MASUK</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value="{{ old('joined_date') }}" required />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL LAHIR</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='birth_date' value="{{ old('birth_date') }}" required />
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NO TELP</label>
                        <input type='number' name='phone' class='form-control' value="{{ old('phone') }}" required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>EMAIL</label>
                        <input type='email' name='email' class='form-control' value="{{ old('email') }}" required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>NO REKENING</label>
                        <input type='text' name='account_number' class='form-control' value="{{ old('account_number') }}" />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>BANK</label>
                        <input type='text' name='account_bank' class='form-control' value="{{ old('account_bank') }}"/>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>ATAS NAMA</label>
                        <input type='text' name='account_name' class='form-control' value="{{ old('account_name') }}" />
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

    <div class="modal fade" id="modal-import-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('client.staff.import.csv') }}" method="post" role="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Import Staff</h4>
                    </div>
                    <div class="modal-body">
                        @include('inc.error-list')

                        <div class="input-group input-group-file" data-plugin="inputGroupFile">
                            <input type="text" placeholder="Choose your file" class="form-control" readonly>
                            <span class="input-group-btn">
                                <span class="btn btn-danger btn-file">
                                    <i class="icon wb-upload" aria-hidden="true"></i>
                                    <input type="file"  name="csv_file" accept=".csv" required>
                                </span>
                            </span>
                        </div>
                        <span class="text-help-default">Allowed file: xls. Click <a href="{{ url('assets/documents/import-staff-template.xls') }}" download>here</a> to download sample file. Note: Save the file as xls file.</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/js/Plugin/input-group-file.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if (count($errors) > 0)
                $('#modal-import-customer').modal();
            @endif
            @include('inc.datepicker')
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.staff.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'id Staff', data: 'id', name: 'id', defaultContent: '-', class: 'text-center'},
                    {title: 'NAMA', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'JABATAN', data: 'position.name', name: 'position.name', defaultContent: '-', class: 'text-center'},
                    {title: 'STATUS', data: 'status', name: 'status', defaultContent: '-', class: 'text-center'},
                    {title: 'DEPARTEMEN', data: 'department.name', name: 'department.name', defaultContent: '-', class: 'text-center'},
                    {title: 'TGL MASUK', data: 'joined_date', name: 'joined_date', defaultContent: '-', class: 'text-center'},
                    {title: 'MASA KERJA', data: 'active_work', name: 'joined_date', defaultContent: '-', class: 'text-center'},
                    {title: 'AKSI', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
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