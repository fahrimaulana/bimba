@extends('backend.layouts.app')

@section('head')
    <title>Report Humas List | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endsection

@section('title')
    Report Humas List
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('client.report.view.index') }}">Report</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('client.report.category.staff') }}">Laporan Kategori Staff</a></li>
    <li class="breadcrumb-item active">Report Humas List</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include ('inc.error-list')
            <form class="form-horizontal" action="{{ route('client.report.humas.humas-detail.display-pdf') }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label class="control-label">Filter Tanggal</label>
                        <div class="input-daterange datepicker">
                            <div class="input-group">
                                <span class="input-group-addon">Dari</span>
                                <input type="text" class="form-control valueDate" name="from_date" value="{{ old('from_date', $from) }}" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">Untuk</span>
                                <input type="text" class="form-control limit-value" name="to_date" value="{{ old('to_date', $to) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @include('client.report.inc.access-level')
                    <div class="form-group col-md-6">
                        <label class="control-label">Sortir Dengan</label>
                        <select name="sort_by" class="form-control select2" data-placeholder="Choose One">
                            <option></option>
                            <option value="nih" {{ (old('sort_by') == 'nih') ? 'selected' : '' }}>NIH</option>
                            <option value="humas_nama" {{ (old('sort_by') == 'humas_nama') ? 'selected' : '' }}>Nama</option>
                            <option value="status" {{ (old('sort_by') == 'status') ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>
                </div>
                @include('client.report.inc.record-limit')
                <div class="row">
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <button type="submit" class="btn btn-primary w100"><i class="fa fa-file-text-o"></i> Lihat Di PDF</button>
                            </div>
                            <div class="form-group col-sm-6">
                                <button type="submit" formaction="{{ route('client.report.humas.humas-detail.download-pdf') }}" class="btn btn-danger w100"><i class="fa fa-file-pdf-o"></i> Download Di  PDF</button>
                            </div>
                            <div class="form-group col-sm-6">
                                <button type="submit" formaction="{{ route('client.report.humas.humas-detail.download-excel') }}" class="btn btn-success w100"><i class="fa fa-file-excel-o"></i> Download Di Excel</button>
                            </div>
                            <div class="form-group col-sm-6">
                                <button type="submit" formaction="{{ route('client.report.humas.humas-detail.download-csv') }}" class="btn btn-info w100"><i class="fa fa-file-excel-o"></i> Download Di CSV</button>
                            </div>
                        </div>
                    </div>
                </div>
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