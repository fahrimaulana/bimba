@extends('backend.layouts.app')

@section('head')
    <title>Ubah Voucher | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Voucher
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.voucher.index') }}">Voucher</a></li>
    <li class="breadcrumb-item active">Ubah Voucher</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Voucher</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.voucher.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Kode Voucher</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code', $voucher->code) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='date' value='{{ old('date', optional($voucher->date)->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Status</label>
                        <select class='form-control select2' name='status' required>
                            <option></option>
                            <option value='Penyerahan' {{ old('status', $voucher->status) == 'Penyerahan' ? 'selected' : '' }}>Penyerahan</option>
                            <option value='Penerimaan' {{ old('status', $voucher->status) == 'Penerimaan' ? 'selected' : '' }}>Penerimaan</option>
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Murid (Humas)</label>
                        <select class='form-control select2' name='student_id' required>
                            <option></option>
                            @foreach ($students as $student)
                            <option value='{{ $student->id }}' {{ old('student_id', $voucher->student_id) == $student->id ? 'selected' : '' }}>{{ $student->nim }} ({{ $student->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Murid Baru</label>
                        <select class='form-control select2' name='invited_student_id' required>
                            <option></option>
                            @foreach ($students as $student)
                            <option value='{{ $student->id }}' {{ old('invited_student_id', $voucher->invited_student_id) == $student->id ? 'selected' : '' }}>{{ $student->nim }} ({{ $student->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nilai Voucher</label>
                        <input type='text' class='form-control separator' value='{{ old('value', $voucher->value) }}' required>
                        <input type='hidden' name='value' class='separator-hidden' value='{{ old('value', $voucher->value) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.voucher.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Ubah</button>
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