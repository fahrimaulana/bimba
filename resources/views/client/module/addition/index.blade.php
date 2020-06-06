@extends('backend.layouts.app')

@section('head')
    <title>Daftar Penerimaan Modul | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Daftar Penerimaan Modul
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Penerimaan Modul</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <div class="row">
                @can('create-module-addition')
                <div class="col-md-6">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Penerimaan Modul
                        </a>
                    </div>
                </div>
                @else
                <div class="col-md-6"></div>
                @endcan
                <div class="col-md-6">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <td><b>Total</b></td>
                            <td>{{ thousandSeparator($transactions->sum(function($trx) { return $trx->qty * $trx->module_price; })) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th>Faktur</th>
                    <th>Tanggal</th>
                    <th>Minggu</th>
                    <th>Kode</th>
                    <th>Nama Modul</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->receipt }}</td>
                    <td>{{ optional($transaction->date)->format('d M Y') }}</td>
                    <td>Ke-{{ $transaction->week }}</td>
                    <td>{{ optional($transaction->module)->code }}</td>
                    <td>{{ optional($transaction->module)->name }}</td>
                    <td>{{ thousandSeparator($transaction->qty) }}</td>
                    <td>{{ thousandSeparator($transaction->module_price * $transaction->qty) }}</td>
                    <td>{{ optional($transaction->module)->type }}</td>
                    <td>
                        @can ('edit-module-addition')
                        <a href="{{ route('client.module.addition.edit', $transaction->id) }}" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Penerimaan Modul">
                            <i class="icon wb-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                        @can ('delete-module-addition')
                        <a class="btn btn-sm btn-icon text-danger tl-tip" data-href="{{ route('client.module.addition.destroy', $transaction->id) }}" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Penerimaan Modul">
                            <i class="icon wb-trash" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Tambah Penerimaan Modul</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.module.addition.store') }}" method="POST">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Faktur</label>
                        <input type='text' name='receipt' class='form-control' value='{{ old('receipt') }}' required />
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='date' value='{{ old('date', now()->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Minggu</label>
                        <select class='form-control select2 category-select' name='week' required>
                            <option></option>
                            @foreach (range(1, 5) as $weekNo)
                            <option value='{{ $weekNo }}' {{ old('week') == $weekNo ? 'selected' : '' }}>
                                Ke-{{ $weekNo }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-8'>
                        <label class='control-label'>Module</label>
                        <select class='form-control select2 category-select' name='module_id' required>
                            <option></option>
                            @foreach ($modules as $module)
                            <option value='{{ $module->id }}' {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                {{ $module->code }} - {{ $module->name }} (Rp. {{ thousandSeparator($module->price) }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Jumlah</label>
                        <input type='text' class='form-control separator' value='{{ old('qty') }}' required>
                        <input type='hidden' name='qty' class='separator-hidden' value='{{ old('qty') }}' required>
                    </div>
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

    <script src='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}'></script>
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @include('inc.datepicker')

            $('.select2').select2({
                'placeholder': 'Pilih salah satu',
                'allowClear': true,
                'width': '100%'
            });

            $('.tl-tip').tooltip();
            @if (count($errors) > 0)
                jQuery("html, body").animate({
                    scrollTop: $('#add-form').offset().top - 100
                }, "slow");
            @endif

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