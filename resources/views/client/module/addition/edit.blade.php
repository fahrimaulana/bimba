@extends('backend.layouts.app')

@section('head')
    <title>Ubah Penerimaan Modul | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Penerimaan Modul
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.module.addition.index') }}">Penerimaan Modul</a></li>
    <li class="breadcrumb-item active">Ubah Penerimaan Modul</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Penerimaan Modul</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.module.addition.update', $transaction->id) }}" method="POST">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Faktur</label>
                        <input type='text' name='receipt' class='form-control' value='{{ old('receipt', $transaction->receipt) }}' required />
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='date' value='{{ old('date', $transaction->date->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Minggu</label>
                        <select class='form-control select2 category-select' name='week' required>
                            <option></option>
                            @foreach (range(1, 5) as $weekNo)
                            <option value='{{ $weekNo }}' {{ old('week', $transaction->week) == $weekNo ? 'selected' : '' }}>
                                Ke-{{ $weekNo }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-8'>
                        <label class='control-label'>Module</label>
                        <select class='form-control select2 category-select' name='module_id' required>
                            <option></option>
                            @foreach ($modules as $module)
                            <option value='{{ $module->id }}' {{ old('module_id', $transaction->module_id) == $module->id ? 'selected' : '' }}>
                                {{ $module->code }} - {{ $module->name }} (Rp. {{ thousandSeparator($module->price) }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Jumlah</label>
                        <input type='text' class='form-control separator' value='{{ old('qty', $transaction->qty) }}' required>
                        <input type='hidden' name='qty' class='separator-hidden' value='{{ old('qty', $transaction->qty) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.module.addition.index') }}" class="btn btn-danger">Kembali</a>
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