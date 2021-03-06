@extends('backend.layouts.app')

@section('head')
    <title>Daftar Order Sertifikat | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Daftar Order Sertifikat
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Order Sertifikat</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <h3 class="text-center">Pemesanan Sertifikat</h3>
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th>NIM</th>
                    <th>Nama Murid</th>
                    <th>Tempat Lahir</th>
                    <th>Tgl Lahir</th>
                    <th>Tgl Masuk</th>
                    <th>Level</th>
                    <th>Minggu</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($transactionDetails as $txDetail)
                @php
                    $tx = optional($txDetail->transaction);
                    $extra = optional($txDetail->extra);
                    $student = optional($tx->student);
                @endphp
                <tr>
                    <td>{{ $student->nim }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->birth_place }}</td>
                    <td>{{ $student->birth_date->format('d/m/y') }}</td>
                    <td>{{ $student->joined_date->format('d/m/y') }}</td>
                    <td>{{ $extra['level'] ? 'Level ' . $extra['level'] : '-' }}</td>
                    <td>{{ $extra['week'] ? 'Ke-' . $extra['week'] : '-' }}</td>
                    <td>{{ $extra['note'] ?: '-' }}</td>
                    <td>
                        @can ('edit-order-certificate')
                        <a href="{{ route('client.order.certificate.edit', $txDetail->id) }}" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Order Sertifikat">
                            <i class="icon wb-edit" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tl-tip').tooltip();
        });
    </script>
@endsection