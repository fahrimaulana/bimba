@extends('backend.layouts.app')

@section('head')
    <title>Ubah Modul | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Modul
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.module.price.index') }}">Modul - Harga</a></li>
    <li class="breadcrumb-item active">Ubah Modul</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Modul</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.module.price.update', $module->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kode</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code', $module->code) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Nama</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $module->name) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Harga</label>
                        <input type='text' class='form-control separator currency' value='{{ old('price', $module->price) }}' required>
                        <input type='hidden' name='price' class='separator-hidden' value='{{ old('price', $module->price) }}' required>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Level <span class='text-danger'>(Optional)</span></label>
                        <input type='text' name='level' class='form-control' value='{{ old('level', $module->level) }}' />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Jenis <span class='text-danger'>(Optional)</span></label>
                        <select class='form-control select2' name='type'>
                            <option></option>
                            <option value='Modul biMBA' {{ old('type', $module->type) == 'Modul biMBA' ? 'selected' : '' }}>Modul biMBA</option>
                            <option value='Modul English' {{ old('type', $module->type) == 'Modul English' ? 'selected' : '' }}>Modul English</option>
                            <option value='ATK' {{ old('type', $module->type) == 'ATK' ? 'selected' : '' }}>ATK</option>
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Minimal Stok</label>
                        <input type='text' class='form-control separator' value='{{ old('min_stock', $module->min_stock) }}' required>
                        <input type='hidden' name='min_stock' class='separator-hidden' value='{{ old('min_stock', $module->min_stock) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.module.price.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Ubah</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection