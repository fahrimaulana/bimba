@extends('backend.layouts.app')

@section('head')
    <title>Sertifikat Beasiswa Pendidikan | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Sertifikat Beasiswa Pendidikan
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Sertifikat Beasiswa Pendidikan</li>
@endpush

@section('content')
    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">Ubah Sertifikat Beasiswa Pendidikan</h3>
        </div>
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <form action="{{ route('client.student.certificate.update', $educationSertificate->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Jumlah Uang</label>
                        <input type="text" class="form-control separator" name="amount" value="{{ old('amount', $educationSertificate->amount) }}" required>
                        <input type="hidden" name="amount" class="separator-hidden" value="{{ old('amount', $educationSertificate->amount) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Jumblah Yang Tertulis</label>
                        <input type="text" class="form-control" name="amount_written" value="{{ old('amount_written', $educationSertificate->amount_written) }}">
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Tanggal Ubah</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='change_date' value="{{ old('change_date', optional($educationSertificate->change_date)->format('d-m-Y')) }}" required />
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Penanggung Jawab</label>
                        <input type="text" class="form-control" name="person_in_charge" value="{{ old('person_in_charge', $educationSertificate->person_in_charge) }}">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.student.certificate.data') }}',
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

            @include('inc.datepicker')
        });
    </script>
@endsection