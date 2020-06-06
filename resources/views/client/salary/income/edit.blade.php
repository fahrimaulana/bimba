@extends('backend.layouts.app')

@section('head')
    <title>Ubah Pendapatan Staff | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Ubah Pendapatan Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.salary.income.index') }}">Daftar Pendapatan Staff</a></li>
    <li class="breadcrumb-item active">Ubah Pendapatan Staff</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Pendapatan Staff</h3>
            @include('inc.error-list')
            @php
                $staff = optional($income->staff);
                $thp = $income->basic_salary + $income->daily + $income->functional + $income->health;
            @endphp
            <form class="form-horizontal" id="form" action="{{ route('client.salary.income.update', $income->id) }}" method="POST">
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
                    @foreach ($allowanceGroups as $group)
                    @php
                        $allowance = (int) optional($income->allowances->where('allowance_group_id', $group->id)->first())->amount;
                    @endphp
                    <div class='form-group col-md-4'>
                        <label class='control-label'>{{ $group->name}}</label>
                    <input type='text' name="allowances[{{ $group->id }}]" class='form-control' value='{{ old('allowances.' . $group->id, $allowance) }}' {{ $group->department_id ? 'disabled' : '' }} />
                    </div>
                    @endforeach
                    <div class="form-group col-md-4">
                        <label class="control-label">Kekurangan</label>
                        <input type='text' class='form-control separator' value='{{ old('underpayment', $income->underpayment) }}' required>
                        <input type='hidden' name='underpayment' class='separator-hidden' value='{{ old('underpayment', $income->underpayment) }}' required>
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Bulan Kekurangan</label>
                        <select class='form-control select2 category-select' name='underpayment_month' required>
                            <option></option>
                            @foreach (shortMonths() as $monthNo => $monthName)
                            <option value='{{ $monthNo }}' {{ old('underpayment_month', $income->underpayment_month) == $monthNo ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Lain - lain</label>
                        <input type='text' class='form-control separator' value='{{ old('other', $income->other) }}' required>
                        <input type='hidden' name='other' class='separator-hidden' value='{{ old('other', $income->other) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.salary.income.index') }}" class="btn btn-danger">Kembali</a>
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