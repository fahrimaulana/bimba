@extends('backend.layouts.app')

@section('head')
    <title>Humas | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Daftar Humas
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Humas</li>
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
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Humas
                        </a>
                        <div class="pull-right">
                            <div class="col-xs-7">
                                <a href="{{ url('../storage/app/uploads/public-relation/fail/import-public-relation-fail.csv') }}" class="btn btn-danger btn-responsive "><i class="icon wb-download"></i> Daftar Humas Gagal Import</a>
                            </div>
                            <div class="col-xs-5">
                                <button type="button" class="btn btn-info btn-responsive pull-right" data-toggle="modal" data-target="#modal-import-customer"><i class="icon wb-download"></i> Import Humas</button>
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
            <h3>Tambah Humas Baru</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.public-relation.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL REG</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='registered_date' value='{{ old('registered_date') }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIH</label>
                        <input type='text' name='nih' class='form-control' value='{{ old('nih') }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value="{{ old('name') }}" required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>STATUS</label>
                        <select class='form-control select2' name='status_id' required>
                            <option></option>
                            @foreach ($status as $status)
                            <option value='{{ $status->id }}' {{ old('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>NO TELP/HP</label>
                        <input type='number' name='phone' class='form-control' value="{{ old('phone') }}" required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>EMAIL</label>
                        <input type='email' name='email' class='form-control' value="{{ old('email') }}" required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>PEKERJAAN</label>
                        <input type='job' name='job' class='form-control' value="{{ old('job') }}" required />
                    </div>
                </div>
                <div class="row">
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

    <div class="modal fade" id="modal-import-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('client.public-relation.import.csv') }}" method="post" role="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Import Humas</h4>
                    </div>
                    <div class="modal-body">
                        @include('inc.error-list')

                        <div class="input-group input-group-file" data-plugin="inputGroupFile">
                            <input type="text" class="form-control" placeholder="Choose your file" readonly>
                            <span class="input-group-btn">
                                <span class="btn btn-danger btn-file">
                                    <i class="icon wb-upload" aria-hidden="true"></i>
                                    <input type="file"  name="csv_file" accept=".csv" required>
                                </span>
                            </span>
                        </div>
                        <span class="text-help-default">Allowed file: CSV. Click <a href="{{ url('assets/documents/import-humas-template.xls') }}" download>here</a> to download sample file. Note: Save the file as CSV file.</span>
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
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.public-relation.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'TGL REG', data: 'registered_date', name: 'registered_date', defaultContent: '-', class: 'text-center'},
                    {title: 'NIH', data: 'nih', name: 'nih', defaultContent: '-', class: 'text-center'},
                    {title: 'NAMA', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'STATUS', data: 'status.name', name: 'status.name', defaultContent: '-', class: 'text-center'},
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
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection