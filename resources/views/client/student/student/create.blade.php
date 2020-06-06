@extends('backend.layouts.app')

@section('head')
    <title>Tambah Murid | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Tambah Murid
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.student.index') }}">Murid</a></li>
    <li class="breadcrumb-item active">Tambah Murid</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Tambah Murid</h3>
            @include('inc.success-notif')
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.student.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" name="trial_student_id" value="{{ optional($trialStudent)->id }}" />
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIM</label>
                        <input type='text' name='nim' class='form-control' value='{{ old('nim') }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', optional($trialStudent)->name) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TMPT LAHIR</label>
                        <input type='text' name='birth_place' class='form-control' value='{{ old('birth_place') }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TGL LAHIR</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='birth_date' value='{{ old('birth_date', '01-01-2000') }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TGL MASUK</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value='{{ old('joined_date', optional(optional($trialStudent)->joined_date)->format('d-m-Y') ?: now()->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>TAHAP</label>
                        <select class='form-control select2' name='phase_id' required>
                            <option></option>
                            @foreach ($phases as $phase)
                            <option value='{{ $phase->id }}' {{ old('phase_id') == $phase->id ? 'selected' : '' }}>{{ $phase->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>KELAS</label>
                        <select class='form-control select2' name='department_id' required>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id', optional($trialStudent)->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
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
                    <div class='form-group col-md-4'>
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
                    <div class='form-group col-md-6'>
                        <label class='control-label'>PETUGAS TRIAL <span class='text-danger'>(Optional)</span></label>
                        <select class='form-control select2' name='trial_teacher_id'>
                            <option></option>
                            @foreach ($staff as $s)
                            <option value='{{ $s->id }}' {{ old('trial_teacher_id', optional($trialStudent)->trial_teacher_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>GURU</label>
                        <select class='form-control select2' name='teacher_id' required>
                            <option></option>
                            @foreach ($staff as $s)
                            <option value='{{ $s->id }}' {{ old('teacher_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>ORANGTUA</label>
                        <input type='text' name='parent_name' class='form-control' value='{{ old('parent_name', optional($trialStudent)->parent_name) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NO TELP/HP</label>
                        <input type='text' name='phone' class='form-control' value='{{ old('phone', optional($trialStudent)->phone) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NOTE <span class='text-danger'>(Optional)</span></label>
                        <select class='form-control select2' name='note_id'>
                            <option></option>
                            @foreach ($studentNotes as $studentNote)
                            <option value='{{ $studentNote->id }}' {{ old('note_id') == $studentNote->id ? 'selected' : '' }}>{{ $studentNote->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>INFO</label>
                        <select class='form-control select2' name='media_source_id' required>
                            <option></option>
                            @foreach ($mediaSources as $mediaSource)
                            <option value='{{ $mediaSource->id }}' {{ old('media_source_id', optional($trialStudent)->media_source_id) == $mediaSource->id ? 'selected' : '' }}>{{ $mediaSource->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>ALAMAT</label>
                        <textarea class='form-control' name='address' required>{{ old('address', optional($trialStudent)->address) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.student.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
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