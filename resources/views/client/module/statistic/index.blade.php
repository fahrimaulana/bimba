@extends('backend.layouts.app')

@section('head')
    <title>Statistik Modul | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Statistik Modul
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Statistik Modul</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            <div class="row">
                <h4 class="text-center">Rekapitulasi Modul</h4>
                <div class="col-md-6 col-lg-4">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Penerimaan</th>
                        </tr>
                        @foreach (range(1, 5) as $weekNo)
                        <tr>
                            <td><b>Minggu Ke-{{ $weekNo }}</b></td>
                            <td>{{ $stats['addition_w' . $weekNo] }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td><b>Total</b></td>
                            <td>{{ $stats->total_addition }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-4">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Pemakaian</th>
                        </tr>
                        @foreach (range(1, 5) as $weekNo)
                        <tr>
                            <td><b>Minggu Ke-{{ $weekNo }}</b></td>
                            <td>{{ $stats['deduction_w' . $weekNo] }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td><b>Total</b></td>
                            <td>{{ $stats->total_deduction }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-4">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Saldo & Status</th>
                        </tr>
                        <tr>
                            <td><b>Saldo Awal</b></td>
                            <td>{{ $stats->initial_balance }}</td>
                        </tr>
                        <tr>
                            <td><b>Saldo Akhir</b></td>
                            <td>{{ $stats->ending_balance }}</td>
                        </tr>
                        <tr>
                            <td><b>Saldo Opname</b></td>
                            <td>{{ $stats->opname_balance }}</td>
                        </tr>
                        <tr>
                            <td><b>Modul Yg Habis</b></td>
                            <td>{{ $stats->out_of_stock }}</td>
                        </tr>
                        <tr>
                            <td><b>Modul < Stok Min</b></td>
                            <td>{{ $stats->less_than_min_stock }}</td>
                        </tr>
                        <tr>
                            <td><b>Modul Yg Selisih</b></td>
                            <td>{{ $stats->diff }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //
        });
    </script>
@endsection