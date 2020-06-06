@extends('backend.layouts.app')

@section('head')
    <title>Daftar Pembayaran Gaji Staff | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Daftar Pembayaran Gaji Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Pembayaran Gaji Staff</li>
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
                    <th>No Rekening</th>
                    <th>Bank</th>
                    <th>Atas Nama</th>
                    <th>Pendapatan</th>
                    <th>Potongan</th>
                    <th>Dibayarkan</th>
                </tr>
                @php
                    $total = $incomeTotal = $deductionTotal = 0;
                @endphp
                @if ($staff->first()->income == 0 && $staff->first()->deduction == 0 )
                    <tr>
                        <td colspan="14" class="text-center"><b>Data tidak ditemukan. Silahkan klik generate gaji untuk mengkalkulasi gaji staff</b></td>
                    </tr>
                @else
                    @foreach ($staff as $s)
                    @php
                        $overpaymentMonth = $s->overpayment_month ? date("(F)", mktime(0, 0, 0, $s->overpayment_month, 1)) : '';

                        $incomeTotal = $s->income;
                        $deductionTotal = $s->deduction;
                        $payment = $s->income - $s->deduction;
                        $total += $payment;
                    @endphp
                    <tr>
                        <td>{{ $s->name }}</td>
                        <td>{{ optional($s->position)->name }}</td>
                        <td>{{ $s->status }}</td>
                        <td>{{ optional($s->department)->name }}</td>
                        <td>{{ yearMonthFormat($s->joined_date) }}</td>
                        <td>{{ $s->account_number }}</td>
                        <td>{{ $s->account_bank }}</td>
                        <td>{{ $s->account_name }}</td>
                        <td>{{ thousandSeparator($s->income) }}</td>
                        <td>{{ thousandSeparator($s->deduction) }}</td>
                        <td>{{ thousandSeparator($payment) }}</td>
                    </tr>
                    @endforeach
                @endif
                <tr>
                    <th colspan="8">Total</th>
                    <th>{{ thousandSeparator($incomeTotal) }}</th>
                    <th>{{ thousandSeparator($deductionTotal) }}</th>
                    <th>{{ thousandSeparator($total) }}</th>
                </tr>
            </table>
        </div>
    </div>
@endsection