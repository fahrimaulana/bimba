@extends('backend.layouts.app')

@section('head')
    <title>Ubah Humas | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Ubah Humas
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.public-relation.index') }}">Humas</a></li>
    <li class="breadcrumb-item active">Ubah Humas</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Humas</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.public-relation.update', $publicRelation->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>TGL REG</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='registered_date' value='{{ old('registered_date', optional($publicRelation->registered_date)->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIH</label>
                        <input type='text' name='nih' class='form-control' value='{{ old('nih', $publicRelation->nih) }}'/>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NAMA</label>
                        <input type='text' name='name' class='form-control' value="{{ old('name', $publicRelation->name) }}" required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>STATUS</label>
                        <select class='form-control select2' name='status_id' required>
                            <option></option>
                            @foreach ($status as $status)
                            <option value='{{ $status->id }}' {{ old('status_id', $publicRelation->status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>NO TELP/HP</label>
                        <input type='number' name='phone' class='form-control' value="{{ old('phone', $publicRelation->phone) }}" required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>EMAIL</label>
                        <input type='email' name='email' class='form-control' value="{{ old('email', $publicRelation->email) }}" required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>PEKERJAAN</label>
                        <input type='job' name='job' class='form-control' value="{{ old('job', $publicRelation->job) }}" required />
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-12'>
                        <label class='control-label'>ALAMAT</label>
                        <textarea class='form-control' name='address' required>{{ old('address', $publicRelation->address) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.public-relation.index') }}" class="btn btn-danger">Batal</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        @include('inc.datepicker')

        $('.select2').select2({
            'placeholder' : 'Pilih salah satu',
            'allowClear' : true,
            'width' : '100%'
        });
    </script>
@endsection