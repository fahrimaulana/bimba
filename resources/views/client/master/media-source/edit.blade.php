@extends('backend.layouts.app')

@section('head')
    <title>Edit Media Source | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Edit Media Source
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.master.media-source.index') }}">Media Source</a></li>
    <li class="breadcrumb-item active">Edit Media Source</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Edit Media Source</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.master.media-source.update', $mediaSource->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $mediaSource->name) }}' required />
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.master.media-source.index') }}" class="btn btn-danger">Back</a>
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