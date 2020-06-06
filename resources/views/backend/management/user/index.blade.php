@extends('backend.layouts.app')

@section('head')
    <title>User List | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/switchery/switchery.min.css') }}'>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    User List
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">User</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can ('create-user')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Add User
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="datatable"></table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Add New User</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route(strtolower(platform()) . '.management.user.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name') }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Email</label>
                        <input type='text' name='email' class='form-control' value='{{ old('email') }}' required />
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Username</label>
                        <input type='text' name='username' class='form-control' value='{{ old('username') }}' required />
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Role</label>
                        <select name="role" id="role" class="form-control select2" data-placeholder="Pilih salah satu" required>
                            <option></option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Password</label>
                        <input type='password' name='password' class='form-control' value='{{ old('password') }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Password Confirmation</label>
                        <input type='password' name='password_confirmation' class='form-control' value='{{ old('password_confirmation') }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Active</label>
                        <br /><input type='checkbox' name='active' data-plugin='switchery' {{ old('active', 1) ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src='{{ asset('global/vendor/switchery/switchery.min.js') }}'></script>
    <script src='{{ asset('global/js/Plugin/switchery.min.js') }}'></script>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route(strtolower(platform()) . '.management.user.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Name', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Email', data: 'email', name: 'email', defaultContent: '-', class: 'text-center'},
                    {title: 'Username', data: 'username', name: 'username', defaultContent: '-', class: 'text-center'},
                    {title: 'Role', data: 'role_display_name', name: 'roles.display_name', defaultContent: '-', class: 'text-center' },
                    {title: 'Active', data: 'active', name: 'active', defaultContent: '-', class: 'text-center'},
                    {title: 'Action', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                initComplete: function() {
                    $('.tl-tip').tooltip();
                    @if (count($errors) > 0)
                        jQuery("html, body").animate({
                            scrollTop: $('#add-form').offset().top - 100
                        }, "slow");
                    @endif
                }
            });

            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });

            $('#add-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#add-form').offset().top - 100
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100
                }, "slow");
            });
        });
    </script>
@endsection