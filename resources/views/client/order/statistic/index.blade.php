@extends('backend.layouts.app')

@section('head')
    <title>Statistik Order | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Statistik Order
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Statistik Order</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            @php
                $kaPrice = $products->where('code', 'KA')->first()->price;
                $mePrice = $products->where('code', 'ME')->first()->price;
                $tasPrice = $products->where('code', 'TAS')->first()->price;
            @endphp
            <div class="row">
                <h4 class="text-center">Rekapitulasi Pemesanan</h4>
                <div class="col-md-6 col-lg-4">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Penerimaan</th>
                        </tr>
                        @php $total = 0 @endphp
                        @foreach (range(1, 5) as $week)
                        @php $total += $moduleTotal[$week] @endphp
                        <tr>
                            <td><b>Minggu Ke-{{ $week }}</b></td>
                            <td>{{ thousandSeparator($moduleTotal[$week]) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td><b>Total</b></td>
                            <td>{{ thousandSeparator($total) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-4">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="3" class="text-center">Kaos Anak</th>
                        </tr>
                        @foreach ($extraSizes as $size)
                        <tr>
                            <td><b>{{ $size }}</b></td>
                            <td>{{ isset($sizeTotal[$size]) ? thousandSeparator($sizeTotal[$size]) : 0 }} Pcs</td>
                            <td>{{ thousandSeparator(isset($sizeTotal[$size]) ? $sizeTotal[$size] * $kaPrice : 0) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-6 col-lg-4">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="3" class="text-center">Lain - lain</th>
                        </tr>
                        @php $meCount = $tasCount = $certificateCount = $stpbCount = $certificateTotal = $stpbTotal = 0; @endphp
                        @foreach ($transactions as $transaction)
                            @php
                                $meCount += $transaction->me_count;
                                $tasCount += $transaction->tas_count;
                                $certificateCount += $transaction->certificate_count;
                                $stpbCount += $transaction->stpb_count;
                                $certificateTotal += $transaction->certificate_total;
                                $stpbTotal += $transaction->stpb_total;
                            @endphp
                        @endforeach
                        <tr>
                            <td><b>ME</b></td>
                            <td>{{ thousandSeparator($meCount) }} Pcs</td>
                            <td>{{ thousandSeparator($meCount * $mePrice) }}</td>
                        </tr>
                        <tr>
                            <td><b>Sertifikat</b></td>
                            <td>{{ thousandSeparator($certificateCount) }} Lbr</td>
                            <td>{{ thousandSeparator($certificateTotal) }}</td>
                        </tr>
                        <tr>
                            <td><b>STPB</b></td>
                            <td>{{ thousandSeparator($stpbCount) }} Lbr</td>
                            <td>{{ thousandSeparator($stpbTotal) }}</td>
                        </tr>
                        <tr>
                            <td><b>TASMBA</b></td>
                            <td>{{ thousandSeparator($tasCount) }} Pcs</td>
                            <td>{{ thousandSeparator($tasCount * $tasPrice) }}</td>
                        </tr>
                        <tr>
                            <td><b>Raport MB</b></td>
                            <td>{{ thousandSeparator($newStudentCount) }} Buku</td>
                            <td>{{ thousandSeparator($newStudentCount * 8000) }}</td>
                        </tr>
                        <tr>
                            <td><b>ATK/Perlengkapan</b></td>
                            <td>{{ thousandSeparator($atkStats->count) }} Item</td>
                            <td>{{ thousandSeparator($atkStats->total) }}</td>
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