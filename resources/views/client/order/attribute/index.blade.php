@extends('backend.layouts.app')

@section('head')
    <title>Daftar Order KA | ME | Tas | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Daftar Order KA | ME | Tas
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Order KA | ME | Tas</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <h3 class="text-center">Pemesanan Kaos, Me, & Tas</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-15">
                        <a id="view-recap-btn" class="btn btn-primary white">
                            <i class="icon wb-eye" aria-hidden="true"></i> Lihat Rekap Pemesanan
                        </a>
                    </div>
                </div>
            </div>
            @php
                $kaTotal = $meTotal = $tasTotal = 0;
                $sizeTotal = [];
            @endphp
            @foreach ($transactions as $transaction)
                @php
                    $kaTotal += $transaction->ka_count;
                    $meTotal += $transaction->me_count;
                    $tasTotal += $transaction->tas_count;
                @endphp
            @endforeach
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th colspan="4"><b>Total</b></th>
                    <th><b>{{ thousandSeparator($kaTotal) }} pcs</b></th>
                    <th></th>
                    <th><b>{{ thousandSeparator($meTotal) }} pcs</b></th>
                    <th><b>{{ thousandSeparator($tasTotal) }} pcs</b></th>
                    <th colspan="2"></th>
                </tr>
                <tr>
                    <th>No Bukti</th>
                    <th>Tanggal</th>
                    <th>NIM</th>
                    <th>Nama Murid</th>
                    <th>Kaos</th>
                    <th>Size</th>
                    <th>Me</th>
                    <th>Tas</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($transactions as $transaction)
                @php
                    $extra = optional($transaction->extra);
                    $sizeTotal[$extra['size']] = isset($sizeTotal[$extra['size']])
                        ? $sizeTotal[$extra['size']] + 1 : 1;
                @endphp
                <tr>
                    <td>{{ $transaction->receipt_no }}</td>
                    <td>{{ $transaction->date->format('d/m/y') }}</td>
                    <td>{{ $transaction->student_nim }}</td>
                    <td>{{ $transaction->student_name }}</td>
                    <td>{{ $transaction->ka_count }}</td>
                    <td>{{ $extra['size'] ?: '-' }}</td>
                    <td>{{ $transaction->me_count }}</td>
                    <td>{{ $transaction->tas_count }}</td>
                    <td>{{ $extra['note'] ?: '-' }}</td>
                    <td>
                        @can ('edit-order-attribute')
                        <a href="{{ route('client.order.attribute.edit', $transaction->id) }}" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Order KA | ME | Tas">
                            <i class="icon wb-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="recap-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3 class="text-center">Rekap Pemesanan</h3>
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
                @foreach ($extraSizes as $size)
                <tr>
                    <td>{{ $size }}</td>
                    <td>{{ str_replace('KA', 'Kaos Anak Size ', $size) }}</td>
                    <td>{{ isset($sizeTotal[$size]) ? thousandSeparator($sizeTotal[$size]) : 0 }} pcs</td>
                </tr>
                @endforeach
                <tr>
                    <td>ME</td>
                    <td>Modul Eksklusif</td>
                    <td>{{ thousandSeparator($meTotal) }} pcs</td>
                </tr>
                <tr>
                    <td>TASMBA</td>
                    <td>Tas Anak biMBA</td>
                    <td>{{ thousandSeparator($tasTotal) }} pcs</td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tl-tip').tooltip();

            $('#view-recap-btn').click(function(e) {
                $('#recap-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#recap-form').offset().top - 100
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#recap-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100
                }, "slow");
            });
        });
    </script>
@endsection