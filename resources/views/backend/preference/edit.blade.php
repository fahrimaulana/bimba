@extends('backend.layouts.app')

@section('head')
    <title>Preference | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/dropify/dropify.min.css') }}'>
@endsection

@section('title')
    Preference
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Preference</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route(strtolower(platform()) . '.preference.update') }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6 offset-md-3'>
                        <label class='control-label'>Logo</label>
                        <input type='file' name='logo' id='input-file-max-fs' data-plugin='dropify' data-show-remove='false' data-height='160px' data-max-file-size='2M' data-default-file='{{ $logo }}' />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Phone</label>
                        <input type='text' name='phone' class='form-control' value='{{ old('phone', $phone) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Email</label>
                        <input type='text' name='email' class='form-control' value='{{ old('email', $email) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Persentase Bagi Hasil</label>
                        <div class="input-group">
                            <input type='text' class='form-control separator' value='{{ old('profit_sharing_percentage', $profitSharingPercentage) }}' required>
                            <input type='hidden' name='profit_sharing_percentage' class='separator-hidden' value='{{ old('profit_sharing_percentage', $profitSharingPercentage) }}' required>
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src='{{ asset('global/vendor/dropify/dropify.min.js') }}'></script>
@endsection