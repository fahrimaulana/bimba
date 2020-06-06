@extends('backend.layouts.app')

@section('head')
    <title>Edit Class Group | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Edit Class Group
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.class-group.index') }}">Class Group</a></li>
    <li class="breadcrumb-item active">Edit Class Group</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Class Group</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.class-group.update', $classGroup->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $classGroup->name) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Total Teacher</label>
                        <input type='text' class='form-control' name='total_teacher' value='{{ old('total_teacher', $classGroup->total_teacher) }}' required>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Total Student</label>
                        <input type='text' class='form-control' name='total_student' value='{{ old('total_student', $classGroup->total_student) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.class-group.index') }}" class="btn btn-danger">Back</a>
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