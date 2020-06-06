@extends('backend.layouts.app')

@section('head')
    <title>Ubah Transaksi Petty Cash | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Transaksi Petty Cash
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.petty-cash.index') }}">Transaksi Petty Cash</a></li>
    <li class="breadcrumb-item active">Ubah Transaksi Petty Cash</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Transaksi Petty Cash</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.petty-cash.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                @php $type = optional($transaction->category)->type === 'Kredit' ? 'credit' : 'debit'; @endphp
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>No Bukti</label>
                        <input type='text' name='receipt_no' class='form-control' value='{{ old('receipt_no', $transaction->receipt_no) }}' required />
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='date' value='{{ old('date', optional($transaction->date)->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Kategori</label>
                        <select class='form-control select2 category-select' name='category_id' required>
                            <option></option>
                            @foreach ($categories as $category)
                            <option data-type='{{ $category->type }}' value='{{ $category->id }}' {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->code }} - {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-8'>
                        <label class='control-label'>Keterangan</label>
                        <textarea class='form-control note-field' name='note' aria-required="">{{ old('note', $transaction->note) }}</textarea>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label type-label'>Nilai</label>
                        <input type='text' class='form-control separator' value='{{ old('value', $transaction->{$type}) }}' required>
                        <input type='hidden' name='value' class='separator-hidden' value='{{ old('value', $transaction->{$type}) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.petty-cash.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}'></script>
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @include('inc.datepicker')

            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });

            $('.category-select').change(function() {
                var type = $('.category-select option:selected').data('type');
                $('.type-label').text(type);
            });

            $('.category-select').change();
        });
    </script>
@endsection