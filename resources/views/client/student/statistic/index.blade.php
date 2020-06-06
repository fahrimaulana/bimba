@extends('backend.layouts.app')

@section('head')
    <title>Statistik Murid | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Statistik Murid
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Murid</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            @foreach ($statistics as $department)
            @php ($activeCount = $department->active_count)
            <div class="row">
                <h4 class="text-center">{{ $department->name }}</h4>
                <div class="col-md-6 col-lg-3">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Realisasi Murid</th>
                        </tr>
                        <tr>
                            <td>Murid Trial Baru</td>
                            <td>{{ $department->trial_count }}</td>
                        </tr>
                        <tr>
                            <td>Murid Baru</td>
                            <td>{{ $department->new_count }}</td>
                        </tr>
                        <tr>
                            <td>Murid Keluar</td>
                            <td>{{ $department->out_count }}</td>
                        </tr>
                        <tr>
                            <td>Murid Aktif</td>
                            <td>{{ $department->active_count }}</td>
                        </tr>
                        <tr>
                            <td>Murid Dhuafa</td>
                            <td>{{ $department->dhuafa_count }}</td>
                        </tr>
                        <tr>
                            <td>Murid BNF</td>
                            <td>{{ $department->bnf_count }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-3">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="3" class="text-center">Berdasarkan Usia</th>
                        </tr>
                        <tr>
                            <td>< 3 Tahun</td>
                            <td>{{ $department->age_below_3_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->age_below_3_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>3 Tahun</td>
                            <td>{{ $department->age_3_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->age_3_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>4 Tahun</td>
                            <td>{{ $department->age_4_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->age_4_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>5 Tahun</td>
                            <td>{{ $department->age_5_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->age_5_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>6 Tahun</td>
                            <td>{{ $department->age_6_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->age_6_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>> 6 Tahun</td>
                            <td>{{ $department->age_after_6_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->age_after_6_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-3">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="3" class="text-center">Lama Belajar</th>
                        </tr>
                        <tr>
                            <td>0 -   3 Bulan</td>
                            <td>{{ $department->study_length_below_3_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->study_length_below_3_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>4 -   6 Bulan</td>
                            <td>{{ $department->study_length_4_to_6_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->study_length_4_to_6_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>7 - 12 Bulan</td>
                            <td>{{ $department->study_length_7_to_12_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->study_length_7_to_12_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>13 - 18 Bulan</td>
                            <td>{{ $department->study_length_13_to_18_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->study_length_13_to_18_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>19 - 24 Bulan</td>
                            <td>{{ $department->study_length_19_to_24_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->study_length_19_to_24_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td>> 24 Bulan</td>
                            <td>{{ $department->study_length_after_24_count }}</td>
                            <td class="right">{{ $activeCount ? round($department->study_length_after_24_count * 100 / $activeCount, 1) : 0 }}%</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-3">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Lain - Lain</th>
                        </tr>
                        <tr>
                            <td>Tahap Persiapan</td>
                            <td>{{ $department->preparation_phase }}</td>
                        </tr>
                        <tr>
                            <td>Tahap Lanjutan</td>
                            <td>{{ $department->advanced_phase }}</td>
                        </tr>
                        <tr>
                            <td>Murid Aktif Kembali</td>
                            <td>{{ $department->re_active_student }}</td>
                        </tr>
                        <tr>
                            <td>Murid Cuti</td>
                            <td>{{ $department->leave_student }}</td>
                        </tr>
                        <tr>
                            <td>Murid Garansi</td>
                            <td>{{ $department->warranty_student }}</td>
                        </tr>
                        <tr>
                            <td>Murid Pindahan</td>
                            <td>{{ $department->transfer_student }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endforeach
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