@extends('backend.layouts.app')

@section('head')
    <title>Daftar Potongan Gaji Staff | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Daftar Potongan Gaji Staff
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Potongan Gaji Staff</li>
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
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpa</th>
                    <th>Tidak Aktif</th>
                    <th>Kelebihan</th>
                    <th>Lain-Lain</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
                @php
                    $total = $sickTotal = $leaveTotal = $alphaTotal = $notActiveTotal = $overpaymentTotal = $otherTotal = 0;
                @endphp
                @if (count($salaryDeductions) === 0)
                    <tr>
                        <td colspan="14" class="text-center"><b>Data tidak ditemukan. Silahkan klik generate gaji untuk mengkalkulasi gaji staff</b></td>
                    </tr>
                @endif
                @foreach ($salaryDeductions as $deduction)
                @php
                    $staff = optional($deduction->staff);
                    $overpaymentMonth = $deduction->overpayment_month ? date("(F)", mktime(0, 0, 0, $deduction->overpayment_month, 1)) : '';

                    $sickTotal += $deduction->sick;
                    $leaveTotal += $deduction->leave;
                    $alphaTotal += $deduction->alpha;
                    $notActiveTotal += $deduction->not_active;
                    $overpaymentTotal += $deduction->overpayment;
                    $otherTotal += $deduction->other;
                    $subTotal = $deduction->sick + $deduction->leave + $deduction->alpha + $deduction->not_active + $deduction->overpayment + $deduction->other;
                    $total += $subTotal;
                @endphp
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ optional($staff->position)->name }}</td>
                    <td>{{ $staff->status }}</td>
                    <td>{{ optional($staff->department)->name }}</td>
                    <td>{{ yearMonthFormat($staff->joined_date) }}</td>
                    <td>{{ thousandSeparator($deduction->sick) }}</td>
                    <td>{{ thousandSeparator($deduction->leave) }}</td>
                    <td>{{ thousandSeparator($deduction->alpha) }}</td>
                    <td>{{ thousandSeparator($deduction->not_active) }}</td>
                    <td>{{ thousandSeparator($deduction->overpayment) . ' ' . $overpaymentMonth }}</td>
                    <td>{{ thousandSeparator($deduction->other) }}</td>
                    <td>{{ thousandSeparator($subTotal) }}</td>
                    <td>
                        @can ('edit-staff-deduction')
                        <a href="{{ route('client.salary.deduction.edit', $deduction->id) }}" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Potongan">
                            <i class="icon wb-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="5">Total</th>
                    <th>{{ thousandSeparator($sickTotal) }}</th>
                    <th>{{ thousandSeparator($leaveTotal) }}</th>
                    <th>{{ thousandSeparator($alphaTotal) }}</th>
                    <th>{{ thousandSeparator($notActiveTotal) }}</th>
                    <th>{{ thousandSeparator($overpaymentTotal) }}</th>
                    <th>{{ thousandSeparator($otherTotal) }}</th>
                    <th>{{ thousandSeparator($total) }}</th>
                    <th colspan="2"></th>
                </tr>
            </table>
        </div>
    </div>
@endsection