@extends('backend.layouts.app')

@section('head')
    <title>Pembayaran Progresif | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Pembayaran Progresif
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Pembayaran Progresif</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-hover table-striped w100">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="14">Pembayaran Progresif</th>
                        </tr>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Departemen</th>
                            <th class="text-center">Masa Kerja</th>
                            <th class="text-center">No Rekening</th>
                            <th class="text-center">Bank</th>
                            <th class="text-center">Atas Nama</th>
                            <th class="text-center">THP</th>
                            <th class="text-center">Kurang</th>
                            <th class="text-center">Lebih</th>
                            <th class="text-center">Bulan</th>
                            <th class="text-center">Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $s)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $s->name }}</td>
                                <td class="text-center">{{ optional($s->position)->name }}</td>
                                <td class="text-center">{{ $s->status }}</td>
                                <td class="text-center">{{ optional($s->department)->name }}</td>
                                <td class="text-center">{{ yearMonthFormat($s->joined_date) }}</td>
                                <td class="text-center">{{ $s->account_number }}</td>
                                <td class="text-center">{{ $s->account_bank }}</td>
                                <td class="text-center">{{ $s->account_name }}</td>
                                <td class="text-center">{{ thousandSeparator($s->thp) }}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center">{{ thousandSeparator($s->thp) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">
                                    <b>Data tidak ditemukan. Silahkan klik generate gaji untuk mengkalkulasi gaji staff.</b>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                columnDefs: [{
                    "paging":   false,
                    orderable: false
                }],
            });
        });
    </script>
@endsection