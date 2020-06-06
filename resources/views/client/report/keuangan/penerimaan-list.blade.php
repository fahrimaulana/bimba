@extends('backend.layouts.app')

@section('head')
    <title>Report Penerimaan List | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endsection

@section('title')
    Report Penerimaan List
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('client.report.view.index') }}">Report</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('client.report.category.keuangan') }}">Laporan Kategori Keuangan</a></li>
    <li class="breadcrumb-item active">Report Penerimaan List</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include ('inc.error-list')
            <form class="form-horizontal" action="{{ route($routeName. '.display-pdf') }}" method="POST">
                {!! csrf_field() !!}
                @include('client.report.inc.record-date')
                <div class="row">
                    @include('client.report.inc.access-level')
                    <div class="form-group col-md-6">
                        <label class="control-label">Sortir Dengan</label>
                        <select name="sort_by" class="form-control select2" data-placeholder="Choose One">
                            <option></option>
                            <option value="kwitansi" {{ (old('sort_by') == 'kwitansi') ? 'selected' : '' }}>Kwitansi</option>
                            <option value="via" {{ (old('sort_by') == 'via') ? 'selected' : '' }}>Via</option>
                        </select>
                    </div>
                </div>
                @include('client.report.inc.record-limit')
                @include('client.report.inc.action-button')
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('global/vendor/switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('global/js/Plugin/switchery.js') }}"></script>
    <script type="text/javascript">
        $('.select2').select2({
            'placeholder' : 'Choose One',
            'allowClear' : true,
            'width' : '100%'
        });
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });
    </script>
@endsection