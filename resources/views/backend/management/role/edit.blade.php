@extends('backend.layouts.app')

@section('head')
    <title>Role Management | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/icheck/icheck.css') }}">
@endsection

@section('title')
    Role Management
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Authorization</a></li>
    <li class="breadcrumb-item active"><a href="{{ route(strtolower(platform()) . '.management.role.index') }}">Role</a></li>
    <li class="breadcrumb-item active">Edit Role</li>
@endpush

@section('content')
    <ul class="blocks-xs-100 blocks-lg-2">
        <li class="index-layout">
            <div class="panel index-panel">
                <div class="panel-body">
                    <div class="row">
                        <ul class="nav nav-pills flex-column">
                            @foreach ($permissionGroups as $groupName => $permissions)
                            <li class="nav-item float-none" role="presentation">
                                <a class="nav-link color-default index" href="#{{ str_replace(' ' , '-', $groupName) }}" data-index="{{ $groupName }}">
                                {{ (strpos($groupName, 'Store') === false) ? $groupName : str_replace('Store', getStoreAlias(), $groupName) }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </li>

        <li class="block-70 no-mrg-btm">
            <form class="form-horizontal" id="form" action="{{ route(strtolower(platform()) . '.management.role.update', $role->id) }}" method="POST">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}

                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Edit Role</h3>
                    </div>
                    <div class="panel-body">
                        @include('inc.error-list')

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="control-label">Name:</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') ?: $role->name }}" required autofocus>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="control-label">Display Name:</label>
                                <input type="text" class="form-control" name="display_name" value="{{ old('display_name') ?: $role->display_name }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Edit Permission: {{ $role->name }} ({{ $role->display_name }})</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                @foreach ($permissionGroups->chunk(ceil(count($permissionGroups) / 2))[0] as $groupName => $permissions)
                                    <h4 class="example-title">
                                        {{ (strpos($groupName, 'Store') === false) ? $groupName : str_replace('Store', getStoreAlias(), $groupName) }}
                                        <div class="pull-right" style="padding-right: 8px;">
                                            Select All
                                            <input type="checkbox" class="icheckbox-primary selectAll" data-group="{{ $groupName }}" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue">
                                        </div>
                                    </h4>
                                    <table class="table">
                                        @foreach ($permissions as $permission)
                                        <tr>
                                            <td class="wid-max">
                                                {{ (strpos($permission->display_name, 'Store') === false) ? $permission->display_name : str_replace('Store', getStoreAlias(), $permission->display_name) }}
                                            </td>
                                            <td><input type="checkbox" class="icheckbox-primary" data-group="{{ $groupName }}" value="{{ $permission->id }}" name="permission_ids[]" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}></td>
                                        </tr>
                                        @endforeach
                                    </table>
                                @endforeach
                            </div>
                            <div class="col-sm-6">
                                @foreach ($permissionGroups->chunk(ceil(count($permissionGroups) / 2))[1] as $groupName => $permissions)
                                    <h4 class="example-title">
                                        {{ (strpos($groupName, 'Store') === false) ? $groupName : str_replace('Store', getStoreAlias(), $groupName) }}
                                        <div class="pull-right" style="padding-right: 8px;">
                                            Select All
                                            <input type="checkbox" class="icheckbox-primary selectAll" data-group="{{ $groupName }}" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue">
                                        </div>
                                    </h4>
                                    <table class="table">
                                        @foreach ($permissions as $permission)
                                        <tr>
                                            <td class="wid-max">
                                                {{ (strpos($permission->display_name, 'Store') === false) ? $permission->display_name : str_replace('Store', getStoreAlias(), $permission->display_name) }}
                                            </td>
                                            <td><input type="checkbox" class="icheckbox-primary" data-group="{{ $groupName }}" value="{{ $permission->id }}" name="permission_ids[]" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}></td>
                                        </tr>
                                        @endforeach
                                    </table>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <a href="{{ route(strtolower(platform()) . '.management.role.index') }}" class="btn btn-danger">Back</a>
                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </li>
    </ul>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/icheck/icheck.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.selectAll').on('ifChecked', function() {
                var groupName = $(this).data('group');
                $('[data-group="' + groupName + '"]').iCheck('check');
            });
            $('.selectAll').on('ifUnchecked', function() {
                var groupName = $(this).data('group');
                $('[data-group="' + groupName + '"]').iCheck('uncheck');
            });
            $('.index').click(function() {
                index = $(this).data('index');
                jQuery("html, body").animate({
                    scrollTop: $('[data-group="' + index + '"]').offset().top - 100 // 66 for sticky bar height
                }, "slow");
            });
        });
    </script>
@endsection