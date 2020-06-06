@extends('backend.layouts.app')

@section('head')
    <title>Role Management | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Role Management
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Authorization</a></li>
    <li class="breadcrumb-item active">Role</li>
@endpush

@section('content')
    <div class="panel panel-bordered">
        <div class="panel-heading">
            <h3 class="panel-title">Role List</h3>
        </div>
        <div class="panel-body">
            @include('inc.success-notif')
            @can ('create-role')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a class="btn btn-primary white" href="{{ route(strtolower(platform()) . '.management.role.create') }}">
                            <i class="icon wb-plus" aria-hidden="true"></i> Add Role
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="table-role">
            </table>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table-role').dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route(strtolower(platform()) . '.management.role.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    { title: 'Name', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    { title: 'Display Name', data: 'display_name', name: 'display_name', defaultContent: '-', class: 'text-center'},
                    { title: 'Action', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center' }
                ],
                initComplete: function() {
                    $('.tl-tip').tooltip();
                }
            });
        });
    </script>
@endsection