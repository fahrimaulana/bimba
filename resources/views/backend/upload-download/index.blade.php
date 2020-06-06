@extends('backend.layouts.app')

@section('head')
    <title>Upload Download  List | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Upload Download  List
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Upload Download </li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can('create-department')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Add Upload Download
                        </a>
                    </div>
                </div>
            </div>
            @endcan

            <table class="table table-bordered table-hover table-striped w100" cellspacing="0" id="datatable"></table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Add New Upload Download </h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.upload-download.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Name</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name') }}' required />
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="control-label">Import File</label>
                        <div class="input-group input-group-file" data-plugin="inputGroupFile">
                            <input type="text" class="form-control" readonly>
                            <span class="input-group-btn">
                                <span class="btn btn-danger btn-file">
                                    <i class="icon wb-upload" aria-hidden="true"></i>
                                    <input type="file" placeholder="Choose your file" name="import_file" accept=".csv" required>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Import File</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('global/js/Plugin/input-group-file.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.upload-download.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Created At', data: 'created_at', name: 'created_at', defaultContent: '-', class: 'text-center'},
                    {title: 'Name', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Action', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
                ],
                responsive: true,
                initComplete: function() {
                    $('.tl-tip').tooltip();
                    @if (count($errors) > 0)
                        jQuery("html, body").animate({
                            scrollTop: $('#add-form').offset().top - 100
                        }, "slow");
                    @endif
                }
            });

            $('#add-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#add-form').offset().top - 100
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100
                }, "slow");
            });
        });
    </script>
@endsection