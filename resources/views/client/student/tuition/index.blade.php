@extends('backend.layouts.app')

@section('head')
    <title>Kartu SPP | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Kartu SPP
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Murid</li>
@endpush

@section('content')
    <div class="panel">
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
                autoWidth: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.student.tuition.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Nim', data: 'nim', name: 'nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Nama', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Golongan', data: 'group', name: 'group', defaultContent: '-', class: 'text-center'},
                    {title: 'Pembayaran SPP', data: 'payment_method', name: 'payment_method', defaultContent: '-', class: 'text-center'},
                    {title: 'biMBA Unit', data: 'unit', name: 'unit', defaultContent: '-', class: 'text-center'},
                    {title: 'Rekening', data: 'account_bank', name: 'account_bank', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                order: [[0, "desc"]],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                }
            });
        });
    </script>
@endsection