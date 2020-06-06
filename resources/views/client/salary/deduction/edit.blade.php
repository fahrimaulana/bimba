@extends('backend.layouts.app')

@section('head')
    <title>Ubah Potongan Staff | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Potongan Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.salary.deduction.index') }}">Daftar Pendapatan Staff</a></li>
    <li class="breadcrumb-item active">Ubah Potongan Staff</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Potongan Staff</h3>
            @include('inc.error-list')
            @php
                $staff = optional($deduction->staff);
                $thp = $deduction->basic_salary + $deduction->daily + $deduction->functional + $deduction->health;
            @endphp
            <form class="form-horizontal" id="form" action="{{ route('client.salary.deduction.update', $deduction->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nama</label>
                        <input type='text' name='receipt_no' class='form-control' value='{{ $staff->name }}' disabled />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Jabatan</label>
                        <input type='text' name='receipt_no' class='form-control' value='{{ optional($staff->position)->name }}' disabled />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Department</label>
                        <input type='text' name='receipt_no' class='form-control' value='{{ optional($staff->department)->name }}' disabled />
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Masa Kerja</label>
                        <input type='text' name='receipt_no' class='form-control' value='{{ yearMonthFormat($staff->joined_date) }}' disabled />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>THP</label>
                        <input type='text' name='thp' class='form-control' value='{{ thousandSeparator($thp) }}' disabled />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Kelebihan</label>
                        <input type='text' class='form-control separator' value='{{ old('overpayment', $deduction->overpayment) }}' required>
                        <input type='hidden' name='overpayment' class='separator-hidden' value='{{ old('overpayment', $deduction->overpayment) }}' required>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Bulan Kelebihan</label>
                        <select class='form-control select2 category-select' name='overpayment_month' required>
                            <option></option>
                            @foreach (shortMonths() as $monthNo => $monthName)
                            <option value='{{ $monthNo }}' {{ old('overpayment_month', $deduction->overpayment_month) == $monthNo ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Lain - lain</label>
                        <input type='text' class='form-control separator' value='{{ old('other', $deduction->other) }}' required>
                        <input type='hidden' name='other' class='separator-hidden' value='{{ old('other', $deduction->other) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.salary.deduction.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection