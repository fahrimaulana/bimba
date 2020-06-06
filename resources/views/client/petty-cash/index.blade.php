@extends('backend.layouts.app')

@section('head')
    <title>Daftar Transaksi Petty Cash | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Daftar Transaksi Petty Cash
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Transaksi Petty Cash</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            @php
                $saldo = $initialSaldo;
                $totalDebit = $totalCredit = 0;
            @endphp
            <div class="row">
                @can('create-petty-cash-transaction')
                <div class="col-md-6">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> Tambah Transaksi Petty Cash
                        </a>
                    </div>
                </div>
                @else
                <div class="col-md-6"></div>
                @endcan
                <div class="col-md-6">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <td><b>Saldo Awal</b></td>
                            <td>Rp {{ thousandSeparator($saldo) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th>No bukti</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Saldo</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($pettyCashTransactions as $transaction)
                @php
                    $saldo = $saldo + $transaction->debit - $transaction->credit;
                    $totalDebit += $transaction->debit;
                    $totalCredit += $transaction->credit;
                @endphp
                <tr>
                    <td>{{ $transaction->receipt_no }}</td>
                    <td>{{ optional($transaction->date)->format('d M Y') }}</td>
                    <td>{{ optional($transaction->category)->code }} - {{ optional($transaction->category)->name }}</td>
                    <td>{{ $transaction->note }}</td>
                    <td>{{ thousandSeparator($transaction->debit) }}</td>
                    <td>{{ thousandSeparator($transaction->credit) }}</td>
                    <td>{{ thousandSeparator($saldo) }}</td>
                    <td>
                        @can ('edit-petty-cash-transaction')
                        <a href="{{ route('client.petty-cash.edit', $transaction->id) }}" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Transaksi Petty Cash">
                            <i class="icon wb-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                        @can ('delete-petty-cash-transaction')
                        <a class="btn btn-sm btn-icon text-danger tl-tip" data-href="{{ route('client.petty-cash.destroy', $transaction->id) }}" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Transaksi Petty Cash">
                            <i class="icon wb-trash" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="4">Total</th>
                    <th>{{ thousandSeparator($totalDebit) }}</th>
                    <th>{{ thousandSeparator($totalCredit) }}</th>
                    <th colspan="2"></th>
                </tr>
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Tambah Transaksi Petty Cash Baru</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.petty-cash.store') }}" method="POST">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>No Bukti</label>
                        <input type='text' name='receipt_no' class='form-control' value='{{ old('receipt_no') }}' required />
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='date' value='{{ old('date', now()->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Kategori</label>
                        <select class='form-control select2 category-select' name='category_id' required>
                            <option></option>
                            @foreach ($categories as $category)
                            <option data-type='{{ $category->type }}' value='{{ $category->id }}' {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->code }} - {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-8'>
                        <label class='control-label'>Keterangan</label>
                        <textarea class='form-control note-field' name='note' aria-required="">{{ old('note') }}</textarea>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label type-label'>Nilai</label>
                        <input type='text' class='form-control separator' value='{{ old('value') }}' required>
                        <input type='hidden' name='value' class='separator-hidden' value='{{ old('value') }}' required>
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

            $('.category-select').change(function() {
                var type = $('.category-select option:selected').data('type');
                $('.type-label').text(type);
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