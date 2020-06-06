@extends('backend.layouts.app')

@section('head')
    <title>Edit Student Out Reason | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Edit Student Out Reason
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.student-out-reason.index') }}">Student Out Reason</a></li>
    <li class="breadcrumb-item active">Edit Student Out Reason</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Student Out Reason</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.student-out-reason.update', $studentOutReason->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Reason</label>
                        <input type='text' name='reason' class='form-control' value='{{ old('reason', $studentOutReason->reason) }}' required />
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.student-out-reason.index') }}" class="btn btn-danger">Back</a>
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection