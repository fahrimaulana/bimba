@extends('backend.layouts.app')

@section('head')
    <title>Ubah Grup Tunjangan Khusus | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Grup Tunjangan Khusus
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.special-allowance-group.index') }}">Grup Tunjangan Khusus</a></li>
    <li class="breadcrumb-item active">Ubah Grup Tunjangan Khusus</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Grup Tunjangan Khusus</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.special-allowance-group.update', $specialAllowanceGroup->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $specialAllowanceGroup->name) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Department <span class='text-danger'>(Optional)</span></label>
                        <select class='form-control select2' name='department_id'>
                            <option></option>
                            @foreach ($departments as $department)
                            <option value='{{ $department->id }}' {{ old('department_id', $specialAllowanceGroup->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.special-allowance-group.index') }}" class="btn btn-danger">Kembali</a>
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