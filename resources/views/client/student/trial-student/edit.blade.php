@extends('backend.layouts.app')

@section('head')
    <title>{{ isset($show) ? 'Detail' : 'Ubah' }} Murid Trial | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    {{ isset($show) ? 'Detail' : 'Ubah' }} Murid Trial
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.student.trial-student.index') }}">Murid Trial</a></li>
    <li class="breadcrumb-item active">{{ isset($show) ? 'Detail' : 'Ubah' }} Murid Trial</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>{{ isset($show) ? 'Detail' : 'Ubah' }} Murid Trial</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.student.trial-student.update', $trialStudent->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TANGGAL MULAI</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value='{{ old('joined_date', optional($trialStudent->joined_date)->format('d-m-Y')) }}' required {{ isset($show) ? 'disabled' : '' }} />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>KELAS</label>
                        <select class='form-control select2' name='department_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id', $trialStudent->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-{{ isset($show) ? 4 : 6 }}'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $trialStudent->name) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                    <div class='form-group col-md-{{ isset($show) ? 4 : 6 }}'>
                        <label class='control-label'>TANGGAL LAHIR</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='birth_date' value='{{ old('birth_date', optional($trialStudent->birth_date)->format('d-m-Y')) }}' required {{ isset($show) ? 'disabled' : '' }} />
                        </div>
                    </div>
                    @isset($show)
                    <div class='form-group col-md-4'>
                        <label class='control-label'>USIA</label>
                        <input type='text' class='form-control' value='{{ optional($trialStudent->birth_date)->age }} Thn' disabled />
                    </div>
                    @endisset
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>GURU TRIAL</label>
                        <select class='form-control select2' name='trial_teacher_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($staff as $staff)
                            <option value='{{ $staff->id }}' {{ old('trial_teacher_id', $trialStudent->trial_teacher_id) == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>INFO</label>
                        <select class='form-control select2' name='media_source_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($mediaSources as $mediaSource)
                            <option value='{{ $mediaSource->id }}' {{ old('media_source_id', $trialStudent->media_source_id) == $mediaSource->id ? 'selected' : '' }}>{{ $mediaSource->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>ORANGTUA</label>
                        <input type='text' name='parent_name' class='form-control' value='{{ old('parent_name', $trialStudent->parent_name) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NO TELP/HP</label>
                        <input type='text' name='phone' class='form-control' value='{{ old('phone', $trialStudent->phone) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>ALAMAT</label>
                        <textarea class='form-control' name='address' required {{ isset($show) ? 'disabled' : '' }}>{{ old('address', $trialStudent->address) }}</textarea>
                    </div>
                </div>
                @if (!isset($show))
                <div class="form-group">
                    <a href="{{ route('client.student.trial-student.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('footer')
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
        });
    </script>
@endsection