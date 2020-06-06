@extends('backend.layouts.app')

@section('head')
    <title>Daftar Murid Trial | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Daftar Murid Trial
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Murid Trial</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can('create-trial-student')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Murid Trial
                        </a>
                        <div class="pull-right">
                            <div class="col-xs-7">
                                <a href="{{ url('../storage/app/uploads/trial-student/fail/import-trial-student-fail.csv') }}" class="btn btn-danger btn-responsive "><i class="icon wb-download"></i> Daftar Murid Trial Gagal Import</a>
                            </div>
                            <div class="col-xs-5">
                                <button type="button" class="btn btn-info btn-responsive pull-right" data-toggle="modal" data-target="#modal-import-customer"><i class="icon wb-download"></i> Import Murid Trial</button>
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
            <h3>Tambah Murid Trial Baru</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.student.trial-student.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL MULAI</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value='{{ old('joined_date', now()->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>KELAS</label>
                        <select class='form-control select2' name='department_id' required>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name') }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL LAHIR</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='birth_date' value='{{ old('birth_date', '01-01-2000') }}' required />
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>GURU TRIAL</label>
                        <select class='form-control select2' name='trial_teacher_id' required>
                            <option></option>
                            @foreach ($staff as $staff)
                            <option value='{{ $staff->id }}' {{ old('trial_teacher_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }} ({{ optional($staff->position)->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>INFO</label>
                        <select class='form-control select2' name='media_source_id' required>
                            <option></option>
                            @foreach ($mediaSources as $mediaSource)
                            <option value='{{ $mediaSource->id }}' {{ old('media_source_id') == $mediaSource->id ? 'selected' : '' }}>{{ $mediaSource->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>ORANGTUA</label>
                        <input type='text' name='parent_name' class='form-control' value='{{ old('parent_name') }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NO TELP/HP</label>
                        <input type='text' name='phone' class='form-control' value='{{ old('phone') }}' required />
                    </div>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>ALAMAT</label>
                        <textarea class='form-control' name='address' required>{{ old('address') }}</textarea>
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
    <div class="modal fade" id="confirm-add-to-master-book-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="get" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Add to Master Book Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure want to add this Murid Trial to master book?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="#" id="confirm-add-to-master-book-modal-action" class="btn btn-success">Add to Master Book</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#confirm-add-to-master-book-modal').on('show.bs.modal', function(event){
            $('#confirm-add-to-master-book-modal-action').attr('href', $(event.relatedTarget).data('href'));
        });
    </script>

    <div class="modal fade" id="modal-import-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('client.trial-student.import.csv') }}" method="post" role="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Import Murid Trial</h4>
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
                        <span class="text-help-default">Allowed file: Excel. Click <a href="{{ url('assets/documents/import-murid-trial-template.xls') }}" download>here</a> to download sample file. Note: Save the file as Excel file.</span>
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
            @include('inc.datepicker')

            $('.select2').select2({
                'placeholder' : 'Pilih Salah Satu',
                'allowClear' : true,
                'width' : '100%'
            });

            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.student.trial-student.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'TGL MULAI', data: 'joined_date', name: 'joined_date', defaultContent: '-', class: 'text-center'},
                    {title: 'NAMA', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'USIA', data: 'age', name: 'birth_date', defaultContent: '-', class: 'text-center'},
                    {title: 'KELAS', data: 'department.name', name: 'department.name', defaultContent: '-', class: 'text-center'},
                    {title: 'GURU TRIAL', data: 'trial_teacher.name', name: 'trialTeacher.name', defaultContent: '-', class: 'text-center'},
                    {title: 'NO TELP/HP', data: 'phone', name: 'phone', defaultContent: '-', class: 'text-center'},
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
        });
    </script>
@endsection