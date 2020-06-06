@extends('backend.layouts.app')

@section('head')
    <title>Change Password | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Change Password
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route(strtolower(platform()) . '.management.user.index') }}">User</a></li>
    <li class="breadcrumb-item active">Change Password</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">

            @include('inc.error-list')
            @include('inc.success-notif')

            <form class="form-horizontal" method="POST" action="{{ isset($id) ? route(strtolower(platform()) . '.management.user.other.update-password', $id) : route(strtolower(platform()) . '.management.user.update-password') }}">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6 col-xs-12">
                        <div class="row">
                            @if (!isset($id))
                            <div class="form-group col-xs-12">
                                <label class="control-label">Old Password</label>
                                <input type="password" name="old_password" class="form-control" value="{{ old('old_password') }}">
                            </div>
                            @endif
                            <div class="form-group col-xs-12">
                                <label class="control-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" value="{{ old('new_password') }}">
                            </div>
                            <div class="form-group col-xs-12">
                                <label class="control-label">Confirm Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" value="{{ old('new_password_confirmation') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="form-group col-md-12 col-lg-6">
                        <button type="submit" class="btn btn-primary pull-right">Update Password</button>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </form>
        </div>
     </div>
@endsection

@section('footer')

@endsection