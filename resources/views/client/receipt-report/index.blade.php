@extends('backend.layouts.app')

@section('head')
    <title>Laporan Tanda Terima | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Laporan Tanda Terima
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Laporan Tanda Terima</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        
                    </div>
                    <iframe src="{{ route('client.receipt-report.viewpdf') }}" type="application/pdf" width="100%" height="650px" type="application/pdf"></iframe>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //
        });
    </script>
@endsection