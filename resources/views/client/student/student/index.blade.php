@extends('backend.layouts.app')

@section('head')
    <title>Buku Induk | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Buku Induk
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Buku Induk</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            @can('create-student')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a href="{{ route('client.student.create') }}" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Murid
                        </a>
                        <div class="pull-right">
                            <div class="col-xs-7">
                                <a href="{{ url('../storage/app/uploads/student/fail/import-student-fail.csv') }}" class="btn btn-danger btn-responsive "><i class="icon wb-download"></i> Daftar Murid Gagal Import</a>
                            </div>
                            <div class="col-xs-5">
                                <button type="button" class="btn btn-info btn-responsive pull-right" data-toggle="modal" data-target="#modal-import-customer"><i class="icon wb-download"></i> Import Murid </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')
    <div class="modal fade" id="confirm-set-as-out-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post" role="form" id="confirm-set-as-out-modal-action">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Set sebagai keluar</h4>
                    </div>
                    <div class="modal-body">
                        <div class='row'>
                            <div class='form-group col-md-12'>
                                <label class='control-label'>Alasan Keluar</label>
                                <select class='form-control select2' id="reason-select2" name='reason_id' required>
                                    <option></option>
                                    @foreach ($studentOutReasons as $reason)
                                    <option value='{{ $reason->id }}' {{ old('reason_id') == $reason->id ? 'selected' : '' }}>{{ $reason->reason }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="form-group col-md-12">
                                <label class="control-label">Tanggal Keluar</label>
                                    <div class='input-group'>
                                        <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                                        <input type='text' class='form-control datepicker' name='out_date' value='{{ old('out_date', '01-01-2000') }}' required />
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Set sebagai keluar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#confirm-set-as-out-modal').on('show.bs.modal', function(event){
            $('#confirm-set-as-out-modal-action').attr('action', $(event.relatedTarget).data('href'));
            $('#reason-select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
    <div class="modal fade" id="confirm-extend-scholarship-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post" role="form" id="confirm-extend-scholarship-modal-action">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Perpanjang Beasiswa</h4>
                    </div>
                    <div class="modal-body">
                        <div class='row'>
                            <div class='form-group col-md-12'>
                                <label class='control-label'>Perpanjang sampai</label>
                                <div class='input-group'>
                                    <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                                    <input type='text' class='form-control extended-date-input' name='extended_date' value='{{ old('extended_date', now()->format('d-m-Y')) }}' required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Perpanjang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#confirm-extend-scholarship-modal').on('show.bs.modal', function(event){
            $('#confirm-extend-scholarship-modal-action').attr('action', $(event.relatedTarget).data('href'));
            $('.extended-date-input').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                clearBtn: true,
                todayBtn: true,
                todayHighlight: true
            });
        });
    </script>
    <div class="modal fade" id="confirm-set-as-active-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post" role="form" id="confirm-set-as-active-modal-action">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Konfirmasi set murid sebagai aktif</h4>
                    </div>
                    <div class="modal-body">
                        <p>Apakah kamu yakin ingin men-set murid ini sebagai aktif?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Set sebagai aktif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('client.student.import.csv') }}" method="post" role="form" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Import Murid</h4>
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
                        <span class="text-help-default">Allowed file: Xls. Click <a href="{{ url('assets/documents/import-murid-template.xls') }}" download>here</a> to download sample file. Note: Save the file as Xls file.</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#confirm-set-as-active-modal').on('show.bs.modal', function(event){
            $('#confirm-set-as-active-modal-action').attr('action', $(event.relatedTarget).data('href'));
        });
    </script>
    <script src='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}'></script>
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
                    url : '{{ route('client.student.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Id Murid', data: 'id', name: 'id', defaultContent: '-', class: 'text-center'},
                    {title: 'TANGGAL MASUK', data: 'joined_date', name: 'joined_date', defaultContent: '-', class: 'text-center'},
                    {title: 'NAMA', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'UMUR', data: 'age', name: 'birth_date', defaultContent: '-', class: 'text-center'},
                    {title: 'INFO', data: 'info', name: 'department.name', defaultContent: '-', class: 'text-center'},
                    {title: 'BEASISWA', data: 'scholarship', name: 'activeScholarship.end_date', defaultContent: '-', class: 'text-center'},
                    {title: 'STATUS', data: 'status', name: 'status', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                order: [[0, "desc"]],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                }
            });
        });
    </script>
@endsection