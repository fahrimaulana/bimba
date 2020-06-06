@extends('backend.layouts.app')

@section('head')
    <title>Edit Public Relation Status | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Edit Public Relation Status
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.public-relation-status.index') }}">Public Relation Status</a></li>
    <li class="breadcrumb-item active">Edit Public Relation Status</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Public Relation Status</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.public-relation-status.update', $publicRelationStatus->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $publicRelationStatus->name) }}' required />
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.public-relation-status.index') }}" class="btn btn-danger">Back</a>
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