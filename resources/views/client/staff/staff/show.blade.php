@extends('backend.layouts.app')

@section('head')
    <title>Detail Staff | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Detail Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.staff.index') }}">Staff</a></li>
    <li class="breadcrumb-item active">Detail Staff</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Detail Staff</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="#" method="POST" enctype="multipart/form-data">
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIK</label>
                        <input type='text' name='nik' class='form-control' value='{{ old('nik', $staff->nik) }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value="{{ old('name', $staff->name) }}" disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>JABATAN</label>
                        <select class='form-control select2' name='position_id' disabled>
                            <option></option>
                            @foreach ($staffPositions as $staffPosition)
                            <option value='{{ $staffPosition->id }}' {{ old('position_id', $staff->position_id) == $staffPosition->id ? 'selected' : '' }}>{{ $staffPosition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>STATUS</label>
                        <select class='form-control select2' name='status' disabled>
                            <option></option>
                            <option value="Active" @if($staff->status == 'Aktif') selected @endif>Aktif</option>
                            <option value="Intern" @if($staff->status == 'Magang') selected @endif>Magang</option>
                            <option value="Resign" @if($staff->status == 'Resign') selected @endif>Resign</option>
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>DEPARTEMEN</label>
                        <select class='form-control select2' name='department_id' disabled>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id', $staff->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL MASUK</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value="{{ old('joined_date', $staff->joined_date->format('d-m-Y')) }}" disabled />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>MASA KERJA</label>
                        <input type='text' class='form-control' value="{{ yearMonthFormat($staff->joined_date) }}" disabled />
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL LAHIR</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='birth_date' value="{{ old('birth_date', $staff->birth_date->format('d-m-Y')) }}" disabled />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>USIA</label>
                        <input type='text' class='form-control' value="{{ yearMonthFormat($staff->birth_date) }}" disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NO TELP</label>
                        <input type='number' name='phone' class='form-control' value="{{ old('phone', $staff->phone) }}" disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>EMAIL</label>
                        <input type='email' name='email' class='form-control' value="{{ old('email', $staff->email) }}" disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>NO REKENING</label>
                        <input type='text' name='account_number' class='form-control' value="{{ old('account_number', $staff->account_number) }}" disabled/>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>BANK</label>
                        <input type='text' name='account_bank' class='form-control' value="{{ old('account_bank', $staff->account_bank) }}" disabled/>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>ATAS NAMA</label>
                        <input type='text' name='account_name' class='form-control' value="{{ old('account_name', $staff->account_name) }}" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.staff.index') }}" class="btn btn-danger">Kembali</a>
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