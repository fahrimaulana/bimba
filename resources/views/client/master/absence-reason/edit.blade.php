@extends('backend.layouts.app')

@section('head')
    <title>Edit Absence Reason | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Edit Absence Reason
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.absence-reason.index') }}">Absence Reason</a></li>
    <li class="breadcrumb-item active">Edit Absence Reason</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Absence Reason</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.absence-reason.update', $absenceReason->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Reason</label>
                        <input type='text' name='reason' class='form-control' value='{{ old('reason', $absenceReason->reason) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Status</label>
                        <select class='form-control select2' id="reason-select2" name='status' required>
                            <option></option>
                            @php
                                use App\Enum\Master\MasterClass\AbsenceReasonStatus;
                            @endphp
                            @foreach (AbsenceReasonStatus::keys() as $status)
                            <option value='{{ $status }}' {{ old('status', $absenceReason->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.absence-reason.index') }}" class="btn btn-danger">Back</a>
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection