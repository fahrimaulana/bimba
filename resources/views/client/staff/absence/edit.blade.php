@extends('backend.layouts.app')

@section('head')
    <title>Ubah Absensi | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Ubah Absensi
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.staff.absence.index') }}">Absensi</a></li>
    <li class="breadcrumb-item active">Ubah Absensi</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Absensi</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.staff.absence.update', $absence->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TGL ABSENSI</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='absent_date' value="{{ old('absent_date', $absence->absent_date->format('d-m-Y')) }}" required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>STAFF</label>
                        <select class='form-control select2' name='staff_id' required>
                            <option></option>
                            @foreach ($staffs as $staff)
                            <option value='{{ $staff->id }}' {{ old('staff_id', $absence->staff_id) == $staff->id ? 'selected' : '' }}>{{ $staff->name }} ({{ $staff->position->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>ABSENSI</label>
                        <select class='form-control select2' name='absence_reason_id' required>
                            <option></option>
                            @foreach ($reasons as $reason)
                            <option value='{{ $reason->id }}' {{ old('absence_reason_id', $absence->absence_reason_id) == $reason->id ? 'selected' : '' }}>{{ $reason->reason }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-12'>
                        <label class='control-label'>KETERANGAN <span class='text-danger'>(Optional)</span></label>
                        <textarea class='form-control' name='note'>{{ old('note', $absence->note) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.staff.absence.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        @include('inc.datepicker')

        $('.select2').select2({
            'placeholder' : 'Pilih salah satu',
            'allowClear' : true,
            'width' : '100%'
        });
    </script>
@endsection