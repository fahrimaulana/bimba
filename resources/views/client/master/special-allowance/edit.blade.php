@extends('backend.layouts.app')

@section('head')
    <title>Ubah Tunjangan Khusus | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Tunjangan Khusus
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.special-allowance.index') }}">Tunjangan Khusus</a></li>
    <li class="breadcrumb-item active">Ubah Tunjangan Khusus</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Tunjangan Khusus</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.special-allowance.update', $specialAllowance->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Grup</label>
                        <select class='form-control select2' name='group_id' required>
                            <option></option>
                            @foreach ($specialAllowanceGroups as $specialAllowanceGroup)
                            <option value='{{ $specialAllowanceGroup->id }}' {{ old('group_id', $specialAllowance->group_id) == $specialAllowanceGroup->id ? 'selected' : '' }}>{{ $specialAllowanceGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nama</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $specialAllowance->name) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Harga</label>
                        <input type='text' class='form-control separator currency' value='{{ old('price', $specialAllowance->price) }}' required>
                        <input type='hidden' name='price' class='separator-hidden' value='{{ old('price', $specialAllowance->price) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.special-allowance.index') }}" class="btn btn-danger">Kembali</a>
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