@extends('backend.layouts.app')

@section('head')
    <title>Daftar Pendapatan Gaji Staff | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Daftar Pendapatan Gaji Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Pendapatan Gaji Staff</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <div class="row">
                @can('generate-staff-salary')
                <div class="col-md-6">
                    <div class="m-b-15">
                        <form action="{{ route('client.salary.generate') }}">
                            @csrf
                            <button class="btn btn-primary white" type="submit">
                                <i class="icon wb-reload" aria-hidden="true"></i> Generate Gaji Staff
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="col-md-6"></div>
                @endcan
            </div>
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Masa Kerja</th>
                    <th>THP</th>
                    @foreach ($allowanceGroups as $group)
                    <th>{{ $group->name }}</th>
                    @endforeach
                    <th>Kekurangan</th>
                    <th>Lain-Lain</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
                @php
                    $total = $thpTotal = $underpaymentTotal = $otherTotal = 0;
                    $allowanceTotal = [];
                @endphp
                @if (count($salaryIncomes) === 0)
                    <tr>
                        <td colspan="14" class="text-center"><b>Data tidak ditemukan. Silahkan klik generate gaji untuk mengkalkulasi gaji staff</b></td>
                    </tr>
                @endif
                @foreach ($salaryIncomes as $income)
                @php
                    $staff = optional($income->staff);
                    $thp = $income->basic_salary + $income->daily + $income->functional + $income->health;
                    $allowances = $income->allowances;
                    $underpaymentMonth = $income->underpayment_month ? date("(F)", mktime(0, 0, 0, $income->underpayment_month, 1)) : '';
                    $subTotal = $thp + $income->underpayment + $income->other;

                    $thpTotal += $thp;
                    $underpaymentTotal += $income->underpayment;
                    $otherTotal += $income->other;
                    $total += $subTotal;
                @endphp
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ optional($staff->position)->name }}</td>
                    <td>{{ $staff->status }}</td>
                    <td>{{ optional($staff->department)->name }}</td>
                    <td>{{ yearMonthFormat($staff->joined_date) }}</td>
                    <td>{{ thousandSeparator($thp) }}</td>
                    @foreach ($allowanceGroups as $group)
                    @php
                        $allowance = (int) optional($allowances->where('allowance_group_id', $group->id)->first())->amount;
                        $subTotal += $allowance;
                        $total += $allowance;
                        $previousAllowance = isset($allowanceTotal[$group->id]) ? $allowanceTotal[$group->id] : 0;
                        $allowanceTotal[$group->id] = $previousAllowance + $allowance;
                    @endphp
                    <th>{{ thousandSeparator($allowance) }}</th>
                    @endforeach
                    <td>{{ thousandSeparator($income->underpayment) . ' ' . $underpaymentMonth }}</td>
                    <td>{{ thousandSeparator($income->other) }}</td>
                    <td>{{ thousandSeparator($subTotal) }}</td>
                    <td>
                        @can ('edit-staff-income')
                        <a href="{{ route('client.salary.income.edit', $income->id) }}" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Pendapatan">
                            <i class="icon wb-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="5">Total</th>
                    <th>{{ thousandSeparator($thpTotal) }}</th>
                    @foreach ($allowanceGroups as $group)
                    <th>{{ thousandSeparator(isset($allowanceTotal[$group->id]) ? $allowanceTotal[$group->id] : 0) }}</th>
                    @endforeach
                    <th>{{ thousandSeparator($underpaymentTotal) }}</th>
                    <th>{{ thousandSeparator($otherTotal) }}</th>
                    <th>{{ thousandSeparator($total) }}</th>
                    <th colspan="2"></th>
                </tr>
            </table>
        </div>
    </div>
@endsection