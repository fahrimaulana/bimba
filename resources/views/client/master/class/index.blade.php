@extends('backend.layouts.app')

@section('head')
    <title>Daftar kelas | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Daftar Kelas
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Kelas</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            @can('create-class-group')
            <form class="form-horizontal" id="form" action="{{ route('client.master.class.update') }}" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="m-b-15">
                            <a id="add-btn" class="btn btn-primary white">
                                <i class="icon wb-plus" aria-hidden="true"></i> Tambahkan Kelas
                            </a>
                            <button type="submit" class="btn btn-primary btn-success pull-right"><i class="icon fa-floppy-o" aria-hidden="true"></i> Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            @endcan
                {!! csrf_field() !!}
                <table id="datatable" class="table table-bordered table-hover table-striped w100" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Kelompok Kelas</th>
                            <th class="text-center">Nama Kelas</th>
                            <th class="text-center">A</th>
                            <th class="text-center">B</th>
                            <th class="text-center">C</th>
                            <th class="text-center">D</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $class)
                            <tr>
                                <td class="text-center">{{ $class->code }}</td>
                                <td>{{ $class->group->name .' ('.$class->group->total_teacher .' guru '.$class->group->total_student .' murid)' }}</td>
                                <td>{{ $class->name }}</td>
                                @foreach($class->classPrices as $classPrice)
                                    <td class="div-class-price text-center">
                                        <input type="text" size="6" class="form-control" name="prices[{{ $classPrice->id }}]" id="input-class-price" value="{{ thousandSeparator($classPrice->price, 2) }}">
                                    </td>
                                @endforeach
                                <td class="text-center">
                                    @can('edit-class-group')
                                    <a href="{{ route('client.master.class.edit', $class->id) }}" class="btn btn-sm btn-icon text-default tl-tip" id="edit-class" data-toggle="tooltip" data-original-title="Ubah Kelas">
                                        <i class="icon wb-edit" aria-hidden="true"></i>
                                    </a>
                                    @endcan
                                    @can('delete-class-group')
                                    <a class="btn btn-sm btn-icon text-danger tl-tip" data-href="{{ route('client.master.class.destroy', $class->id) }}" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Kelas"><i class="icon wb-trash" aria-hidden="true"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Tambahkan kelas Baru</h3>
            <form class="form-horizontal" id="form" action="{{ route('client.master.class.store') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kelompok Kelas</label>
                        <select class='form-control select2' name='group_id' required>
                            <option></option>
                            @foreach ($groups as $group)
                            <option value='{{ $group->id }}' {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }} ({{ $group->total_teacher ." Teacher, ". $group->total_student }} Student)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Beasiswa</label>
                        <select class='form-control select2' name='scholarship'>
                            <option></option>
                            <option value="Dhuafa" {{ old('scholarship') == 'Dhuafa' ? 'selected' : '' }}>Dhuafa</option>
                            <option value="BNF" {{ old('scholarship' == 'BNF' ? 'selected' : '') }}>BNF</option>
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kode</label>
                        <input type='text' class='form-control' name='code' value='{{ old('code') }}' required>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Nama Kelas</label>
                        <input type='text' class='form-control' name='name' value='{{ old('name') }}' required>
                    </div>
                </div>
                <div class='row'>
                    @foreach($grades as $grade)
                        <div class='form-group col-md-3'>
                            <label class='control-label'>{{ $grade->name }}</label>
                            <input type='number' class='form-control' name='grade[{{$grade->id}}]'>
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
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

             $('#datatable').DataTable({
                columnDefs: [{
                    "paging":   false,
                    orderable: false,
                    targets: [2,3,4,5,6,7]
                }],
            });

            $('#datatable tbody tr').on("click", '#edit-class', function() {
                $(this).parent().siblings('.div-class-price').find('#input-class-price');
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