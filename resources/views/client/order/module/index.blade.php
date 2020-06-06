@extends('backend.layouts.app')

@section('head')
    <title>Daftar Order Module | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Daftar Order Module
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Daftar Order Module</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    @foreach (range(1, 5) as $week)
                    <th colspan="4" class="text-center" style="background-color: #fdf2d0">
                        PEMESANAN MODUL MINGGU KE-{{ $week }}
                    </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach (range(1, 5) as $week)
                    <th>KODE</th>
                    <th>JML</th>
                    <th>HRG</th>
                    <th>STS</th>
                    @endforeach
                </tr>
                @php
                    $total = []
                @endphp
                @foreach (range(0, $maxModuleCount - 1) as $i)
                <tr>
                    @foreach (range(1, 5) as $week)
                        @php
                            $total[$week] = isset($total[$week])
                                ? $total[$week] + optional($modules[$week])[$i]["w{$week}_price"]
                                : optional($modules[$week])[$i]["w{$week}_price"];
                        @endphp
                        <td>{{ optional($modules[$week])[$i]["code"] }}</td>
                        <td>{{ thousandSeparator(optional($modules[$week])[$i]["w{$week}_qty"]) ?: '' }}</td>
                        <td>{{ thousandSeparator(optional($modules[$week])[$i]["w{$week}_price"]) ?: '' }}</td>
                        @if (!optional($modules[$week])[$i])
                        <td></td>
                        @elseif (optional($modules[$week])[$i]["w{$week}_status"] == 0)
                        <td><i class="icon text-danger wb-triangle-down"></i></td>
                        @elseif (optional($modules[$week])[$i]["w{$week}_status"] == 1)
                        <td><i class="icon text-success wb-triangle-up"></i></td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
                <tr>
                    @foreach (range(1, 5) as $week)
                    <td colspan="2"><b>Total</b></td>
                    <td colspan="2"><b>{{ thousandSeparator($total[$week]) }}</b></td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection