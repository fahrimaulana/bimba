@extends('backend.layouts.app')

@section('head')
    <title>Edit Class | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Edit Class
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.class.index') }}">Class</a></li>
    <li class="breadcrumb-item active">Edit Class</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Class</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.class.update', $class->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kode</label>
                        <input type='text' class='form-control' name='code' value='{{ old('code', $class->code) }}' disabled>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Nama Kelas</label>
                        <input type='text' class='form-control' name='name' value='{{ old('name', $class->name) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.class.index') }}" class="btn btn-danger">Back</a>
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
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