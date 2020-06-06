@extends('backend.layouts.app')

@section('head')
    <title>Ubah Order Sertifikat | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Order Sertifikat
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.order.certificate.index') }}">Order Sertifikat</a></li>
    <li class="breadcrumb-item active">Ubah Order Sertifikat</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Order Sertifikat</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.order.certificate.update', $transactionDetail->id) }}" method="POST">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                @php
                    $tx = optional($transactionDetail->transaction);
                    $extra = optional($transactionDetail->extra);
                    $student = optional($tx->student);
                @endphp
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>NIM</label>
                        <input type='text' class='form-control' value='{{ $student->nim }}' disabled />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nama Murid</label>
                        <input type='text' class='form-control' value='{{ $student->name }}' disabled />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Tempat Lahir</label>
                        <input type='text' class='form-control' value='{{ $student->birth_place }}' disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Tgl Lahir</label>
                        <input type='text' class='form-control' value='{{ $student->birth_date->format('d/m/y') }}' disabled />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Tgl Masuk</label>
                        <input type='text' class='form-control' value='{{ $student->joined_date->format('d/m/y') }}' disabled />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nama Ayah / Ibu</label>
                        <input type='text' class='form-control' value='{{ $student->parent_name }}' disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Level</label>
                        <select class='form-control select2 category-select' name='level' required>
                            <option></option>
                            @foreach (range(1, 4) as $level)
                            <option value='{{ $level }}' {{ old('level', $extra['level']) == $level ? 'selected' : '' }}>
                                Level {{ $level }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Minggu</label>
                        <select class='form-control select2 category-select' name='week' required>
                            <option></option>
                            @foreach (range(1, 5) as $weekNo)
                            <option value='{{ $weekNo }}' {{ old('week', $extra['week']) == $weekNo ? 'selected' : '' }}>
                                Ke-{{ $weekNo }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Keterangan</label>
                        <input type='text' name='note' class='form-control' value='{{ old('note', $extra['note']) }}' />
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.order.certificate.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
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