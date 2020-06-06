@extends('backend.layouts.app')

@section('head')
    <title>Pindah Gol | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Pindah Gol
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Pindah Gol</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can('create-student-note')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Pindah Golongan
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
            <h3>Pindah Golongan</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.student.moveGrades.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Murid</label>
                        <select class="form-control select2" name="student_id" required>
                            <option></option>
                            @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->nim . ' - '. $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>GOL</label>
                        <select class='form-control select2' name='class_id' required>
                            <option></option>
                            @foreach ($classes->groupBy('group.name') as $classGroupName => $classes)
                                <optgroup label="{{ $classGroupName }}">
                                    @foreach ($classes as $class)
                                    <option value='{{ $class->id }}' {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>KD</label>
                        <select class='form-control select2' name='grade_id' required>
                            <option></option>
                            @foreach ($grades as $grade)
                            <option value='{{ $grade->id }}' {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Keterangan <span class='text-danger'>(Optional)</span></label>
                        <textarea class='form-control' name='note'>{{ old('note', $student->note) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.student.moveGrades.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Nim', data: 'nim', name: 'nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Nama Murid', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Kelas', data: 'kelas', name: 'kelas', defaultContent: '-', class: 'text-center'},
                    {title: 'Bill Payment', data: 'bill_payment', name: 'bill_payment', defaultContent: '-', class: 'text-center'},
                    {title: 'Gol', data: 'gol', name: 'gol', defaultContent: '-', class: 'text-center'},
                    {title: 'Kd', data: 'kd', name: 'kd', defaultContent: '-', class: 'text-center'},
                    {title: 'SPP', data: 'payment_method', name: 'payment_method', defaultContent: '-', class: 'text-center'},
                    {title: 'Keterangan', data: 'note', name: 'note', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                order: [[0, "desc"]],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
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