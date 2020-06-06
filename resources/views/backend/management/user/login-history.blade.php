@extends('backend.layouts.app')

@section('head')
    <title>User Login Histories | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    User Login Histories
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">User Login Histories</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">User Login Histories</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route(strtolower(platform()) . '.management.user.login-history.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    { title: 'Login at', data : 'created_at', name: 'created_at', class: 'text-center'},
                    { title: 'Username', data : 'user.username', name: 'user.username', class: 'text-center'},
                    { title: 'IP Address', data : 'ip', name: 'ip', class: 'text-center'},
                    { title: 'Browser', data : 'browser', name: 'browser', class: 'text-center'},
                    { title: 'Platform', data : 'platform', name: 'platform', class: 'text-center'},
                ],
                order: [[ 0, "desc" ]]
            });
        });
    </script>
@endsection