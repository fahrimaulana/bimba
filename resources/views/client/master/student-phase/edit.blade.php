@extends('backend.layouts.app')

@section('head')
    <title>Edit Student Phase | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Edit Student Phase
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.student-phase.index') }}">Student Phase</a></li>
    <li class="breadcrumb-item active">Edit Student Phase</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Student Phase</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.student-phase.update', $studentPhase->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $studentPhase->name) }}' required />
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.student-phase.index') }}" class="btn btn-danger">Back</a>
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