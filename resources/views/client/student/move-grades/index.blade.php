@extends('backend.layouts.app')

@section('head')
    <title>Pindah Gol {{ env('APP_NAME') }}</title>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-15">
                        <a id="view-recap-btn" class="btn btn-primary white">
                            <i class="icon wb-edit" aria-hidden="true"></i> Pindah Gol
                        </a>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-hover table-striped w100">
                <thead>
                    <tr>
                        <th class="text-center" colspan="5">PROFIL</th>
                        <th class="text-center" colspan="3">DATA AWAL</th>
                        <th class="text-center" colspan="3">PERUBAHAN</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">NIM</th>
                        <th class="text-center">NAMA</th>
                        <th class="text-center">KELAS</th>
                        <th class="text-center">BILL PAYMENT</th>
                        <th class="text-center">GOL</th>
                        <th class="text-center">KD</th>
                        <th class="text-center">SPP</th>
                        <th class="text-center">GOL</th>
                        <th class="text-center">KD</th>
                        <th class="text-center">SPP</th>
                        <th class="text-center">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @forelse ($classLogs as $result)
                    <tr>
                        <td class="text-center">{{ $no }}</td>
                        <td class="text-center">{{ $result->nim }}</td>
                        <td class="text-center">{{ $result->student_name }}</td>
                        <td class="text-center">{{ $result->department_name }}</td>
                        <td class="text-center">{{ $result->department_name }} - 00246 - {{ $result->nim }}</td>
                        <td class="text-center">{{ $result->old_class_code }}</td>
                        <td class="text-center">{{ $result->old_grade_name }}</td>
                        <td class="text-center">{{ thousandSeparator($result->old_price)}}</td>
                        <td class="text-center">{{ $result->new_class_code }}</td>
                        <td class="text-center">{{ $result->new_grade_name }}</td>
                        <td class="text-center">{{ thousandSeparator($result->new_price)}}</td>
                        <td class="text-center">{{ $result->note }}</td>
                    </tr>
                    @php $no++; @endphp
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="recap-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Pindah Golongan</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.student.move-grades.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Murid</label>
                        <select class="form-control select2 student-change" name="student_id" required>
                            <option></option>
                             @foreach ($students as $student)
                                <option value="{{ $student->id }}" data-student-fee="{{ $student->fee }}"" data-class-id="{{ $student->class_id }}" data-grade-id="{{ $student->grade_id }}">{{ $student->nim .' - '. $student->name .' - '. $student->fee }}</option>
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
                        <input type="hidden" name="old_class_id" value="{{ old('old_class_id') }}" class="old-class-id">
                        <input type="hidden" name="old_grade_id" value="{{ old('old_grade_id') }}" class="old-grade-id">
                        <input type="hidden" name="old_student_fee" value="{{ old('old_student_fee') }}" class="old-student-fee">
                        <label class='control-label'>Keterangan <span class='text-danger'>(Optional)</span></label>
                        <textarea class='form-control' name='note'>{{ old('note') }}</textarea>
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
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tl-tip').tooltip();

            $('#view-recap-btn').click(function(e) {
                $('#recap-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#recap-form').offset().top - 100
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#recap-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100
                }, "slow");
            });

            $('.student-change').change(function() {
                var gradeId = $('.student-change option:selected').data('grade-id');
                var classId = $('.student-change option:selected').data('class-id');
                var studentFee = $('.student-change option:selected').data('student-fee');
                $('.old-grade-id').val(gradeId);
                $('.old-class-id').val(classId);
                $('.old-student-fee').val(studentFee);
                return;
            });
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection