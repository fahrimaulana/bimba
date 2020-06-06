@extends('backend.layouts.app')

@section('head')
    <title>{{ isset($show) ? 'Detail' : 'Ubah' }} Murid | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    {{ isset($show) ? 'Detail' : 'Ubah' }} Murid
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.student.index') }}">Murid</a></li>
    <li class="breadcrumb-item active">{{ isset($show) ? 'Detail' : 'Ubah' }} Murid</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>{{ isset($show) ? 'Detail' : 'Ubah' }} Murid</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIM</label>
                        <input type='text' name='nim' class='form-control' value='{{ old('nim', $student->nim) }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $student->name) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TMPT LAHIR</label>
                        <input type='text' name='birth_place' class='form-control' value='{{ old('birth_place', $student->birth_place) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TGL LAHIR</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='birth_date' value='{{ old('birth_date', optional($student->birth_date)->format('d-m-Y')) }}' required {{ isset($show) ? 'disabled' : '' }} />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>TG MASUK</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value='{{ old('joined_date', optional($student->joined_date)->format('d-m-Y')) }}' required {{ isset($show) ? 'disabled' : '' }} />
                        </div>
                    </div>
                </div>
                @isset($show)
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>USIA</label>
                        <input type='text' class='form-control' value='{{ yearMonthFormat(optional($student->birth_date)) }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>LAMA BELAJAR</label>
                    <input type='text' class='form-control' value='{{ yearMonthFormat(optional($student->joined_date)) }}' disabled />
                    </div>
                </div>
                @endisset
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>TAHAP</label>
                        <select class='form-control select2' name='phase_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($phases as $phase)
                            <option value='{{ $phase->id }}' {{ old('phase_id', $student->phase_id) == $phase->id ? 'selected' : '' }}>{{ $phase->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @isset($show)
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL KELUAR</label>
                        <input type='text' class='form-control' value='{{ optional($student->out_date)->format('d M Y') }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>ALASAN</label>
                        <input type='text' class='form-control' value='{{ optional($student->outReason)->reason }}' disabled />
                    </div>
                </div>
                @endisset
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>KELAS</label>
                        <select class='form-control select2' name='department_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>GOL</label>
                        <select class='form-control select2' name='class_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($classes->groupBy('group.name') as $classGroupName => $classes)
                                <optgroup label="{{ $classGroupName }}">
                                    @foreach ($classes as $class)
                                    <option value='{{ $class->id }}' {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>{{ $class->code }} - {{ $class->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>KD</label>
                        <select class='form-control select2' name='grade_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($grades as $grade)
                            <option value='{{ $grade->id }}' {{ old('grade_id', $student->grade_id) == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @isset($show)
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>SPP</label>
                        <input type='text' class='form-control' value='Rp. {{ thousandSeparator($student->fee) }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>STATUS</label>
                        <input type='text' class='form-control' value='{{ $student->indoStatus }}' disabled />
                    </div>
                </div>
                @endisset
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>PETUGAS TRIAL <span class='text-danger'>(Optional)</span></label></label>
                        <select class='form-control select2' name='trial_teacher_id' {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($staff as $s)
                            <option value='{{ $s->id }}' {{ old('trial_teacher_id', $student->trial_teacher_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>GURU</label>
                        <select class='form-control select2' name='teacher_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($staff as $s)
                            <option value='{{ $s->id }}' {{ old('teacher_id', $student->teacher_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>ORANGTUA</label>
                        <input type='text' name='parent_name' class='form-control' value='{{ old('parent_name', $student->parent_name) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NO TELP/HP</label>
                        <input type='text' name='phone' class='form-control' value='{{ old('phone', $student->phone) }}' required {{ isset($show) ? 'disabled' : '' }} />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NOTE <span class='text-danger'>(Optional)</span></label>
                        <select class='form-control select2' name='note_id' {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($studentNotes as $studentNote)
                            <option value='{{ $studentNote->id }}' {{ old('note_id', $student->note_id) == $studentNote->id ? 'selected' : '' }}>{{ $studentNote->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>INFO</label>
                        <select class='form-control select2' name='media_source_id' required {{ isset($show) ? 'disabled' : '' }}>
                            <option></option>
                            @foreach ($mediaSources as $mediaSource)
                            <option value='{{ $mediaSource->id }}' {{ old('media_source_id', $student->media_source_id) == $mediaSource->id ? 'selected' : '' }}>{{ $mediaSource->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-12'>
                        <label class='control-label'>ALAMAT</label>
                        <textarea class='form-control' name='address' {{ isset($show) ? 'disabled' : '' }} required>{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>
                @if (!isset($show))
                <div class="form-group">
                    <a href="{{ route('client.student.index') }}" class="btn btn-danger">Kembali</a>
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