@extends('backend.layouts.app')

@section('head')
    <title>Daftar Penerimaan | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Daftar Penerimaan
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Penerimaan</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
             @can('create-voucher')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a class="btn btn-primary white" href="{{ route('client.transaction.create') }}">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Penerimaan
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
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
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.transaction.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Kwitansi', data: 'receipt_no', name: 'receipt_no', defaultContent: '-', class: 'text-center'},
                    {title: 'VIA', data: 'payment_method', name: 'payment_method', defaultContent: '-', class: 'text-center'},
                    {title: 'Tanggal', data: 'date', name: 'date', defaultContent: '-', class: 'text-center'},
                    {title: 'NIM', data: 'student.nim', name: 'student.nim', defaultContent: '-', class: 'text-center'},
                    {title: 'Nama', data: 'student.name', name: 'student.name', defaultContent: '-', class: 'text-center'},
                    {title: 'Total', data: 'total', name: 'total', defaultContent: '-', class: 'text-center'},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                    @if (count($errors) > 0)
                        jQuery("html, body").animate({
                            scrollTop: $('#add-form').offset().top - 100
                        }, "slow");
                    @endif
                }
            });
        });
    </script>
@endsection