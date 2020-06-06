@extends('backend.layouts.app')

@section('head')
    <title>Edit Department | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Edit Department
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.department.index') }}">Department</a></li>
    <li class="breadcrumb-item active">Edit Department</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Department</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.department.update', $department->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $department->name) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Code</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code', $department->code) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Price</label>
                        <input type='text' class='form-control separator currency' value='{{ old('price', $department->price) }}' required>
                        <input type='hidden' name='price' class='separator-hidden' value='{{ old('price', $department->price) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.department.index') }}" class="btn btn-danger">Back</a>
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection