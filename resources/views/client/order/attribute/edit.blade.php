@extends('backend.layouts.app')

@section('head')
    <title>Ubah Order KA | ME | Tas | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Order KA | ME | Tas
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.order.attribute.index') }}">Order KA | ME | Tas</a></li>
    <li class="breadcrumb-item active">Ubah Order KA | ME | Tas</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Order KA | ME | Tas</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.order.attribute.update', $transaction->id) }}" method="POST">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                @php
                    $extra = optional($transaction->extra);
                @endphp
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>No Bukti</label>
                        <input type='text' class='form-control' value='{{ $transaction->receipt_no }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Tanggal</label>
                        <input type='text' class='form-control' value='{{ $transaction->date->format('d/m/y') }}' disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>NIM</label>
                        <input type='text' class='form-control' value='{{ $transaction->student_nim }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Nama Murid</label>
                        <input type='text' class='form-control' value='{{ $transaction->student_name }}' disabled />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Size</label>
                        <select class='form-control select2 category-select' name='size' required>
                            <option></option>
                            @foreach ($extraSizes as $size)
                            <option value='{{ $size }}' {{ old('size', $extra['size']) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Keterangan</label>
                        <input type='text' name='note' class='form-control' value='{{ old('note', $extra['note']) }}' />
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.order.attribute.index') }}" class="btn btn-danger">Kembali</a>
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
        });
    </script>
@endsection