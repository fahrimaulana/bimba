@extends('backend.layouts.app')

@section('head')
    <title>Bagi Hasil | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
@endsection

@section('title')
    Bagi Hasil
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Bagi Hasil</li>
@endpush
@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <iframe src="{{ route('client.profit-sharing.view-pdf') }}" type="application/pdf" width="100%" height="650px" type="application/pdf"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
@endsection