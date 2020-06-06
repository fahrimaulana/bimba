@extends('backend.layouts.app')

@section('head')
    <title>Daftar Harga Modul | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Daftar Harga Modul
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Harga Modul</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @can('create-module-price')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Modul
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
            <h3>Tambah Modul Baru</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.module.price.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kode</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code') }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Nama</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name') }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Harga</label>
                        <input type='text' class='form-control separator currency' value='{{ old('price') }}' required>
                        <input type='hidden' name='price' class='separator-hidden' value='{{ old('price') }}' required>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Level <span class='text-danger'>(Optional)</span></label>
                        <input type='text' name='level' class='form-control' value='{{ old('level') }}' />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Jenis</label>
                        <select class='form-control select2' name='type' required>
                            <option></option>
                            <option value='Modul biMBA' {{ old('type') == 'Modul biMBA' ? 'selected' : '' }}>Modul biMBA</option>
                            <option value='Modul English' {{ old('type') == 'Modul English' ? 'selected' : '' }}>Modul English</option>
                            <option value='ATK' {{ old('type') == 'ATK' ? 'selected' : '' }}>ATK</option>
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Minimal Stok</label>
                        <input type='text' class='form-control separator' value='{{ old('min_stock') }}' required>
                        <input type='hidden' name='min_stock' class='separator-hidden' value='{{ old('min_stock') }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Batal</button>
                    <button type="submit" class="btn btn-primary pull-right">Simpan</button>
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
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });

            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('client.module.price.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    {title: 'Kode', data: 'code', name: 'code', defaultContent: '-', class: 'text-center'},
                    {title: 'Nama', data: 'name', name: 'name', defaultContent: '-', class: 'text-center'},
                    {title: 'Harga', data: 'price', name: 'price', defaultContent: '-', class: 'text-center', searchable: false},
                    {title: 'Level', data: 'level', name: 'level', defaultContent: '-', class: 'text-center'},
                    {title: 'Jenis', data: 'type', name: 'type', defaultContent: '-', class: 'text-center'},
                    {title: 'Minimal Stok', data: 'min_stock', name: 'min_stock', defaultContent: '-', class: 'text-center', searchable: false},
                    {title: 'Aksi', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center'}
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