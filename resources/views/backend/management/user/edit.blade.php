@extends('backend.layouts.app')

@section('head')
    <title>Edit User | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/switchery/switchery.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Edit User
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route(strtolower(platform()) . '.management.user.index') }}">User</a></li>
    <li class="breadcrumb-item active">Edit User</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body" id="add-form">
            <h3>Edit User</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route(strtolower(platform()) . '.management.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $user->name) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Email</label>
                        <input type='text' name='email' class='form-control' value='{{ old('email', $user->email) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Username</label>
                        <input type='text' name='username' class='form-control' value='{{ old('username', $user->username) }}' required />
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Role</label>
                        <select name="role" id="role" class="form-control select2" data-placeholder="Pilih salah satu" required>
                            <option></option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role', optional($user->role)->id) == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Active</label>
                        <br /><input type='checkbox' name='active' data-plugin='switchery' {{ old('active', $user->active) ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route(strtolower(platform()) . '.management.user.index') }}" class="btn btn-danger">Back</a>
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src='{{ asset('global/vendor/switchery/switchery.min.js') }}'></script>
    <script src='{{ asset('global/js/Plugin/switchery.min.js') }}'></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
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