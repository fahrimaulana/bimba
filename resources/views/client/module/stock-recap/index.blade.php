@extends('backend.layouts.app')

@section('head')
    <title>Rekap Stok | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    Rekap Stok
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Rekap Stok</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <form class="form-horizontal" action="{{ route('client.module.stock-recap.change-opname') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="m-b-15 clearfix">
                            @can('change-module-stock-opname')
                            <button type="submit" class="btn btn-primary btn-success pull-right update-button" {{ $errors->any() ? '' : 'disabled' }}>
                                <i class="icon fa-floppy-o" aria-hidden="true"></i> Simpan Perubahan
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover table-striped w100">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">Stok Modul</th>
                            @foreach (range(1, 5) as $weekNo)
                            <th colspan="2" class="text-center">Minggu Ke-{{ $weekNo }}</th>
                            @endforeach
                            <th colspan="2" class="text-center">Total</th>
                            <th colspan="5" class="text-center">Status & Kontrol</th>
                        </tr>
                        <tr>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Saldo Awal</th>
                            @foreach (range(1, 6) as $weekWithTotal)
                            <th class="text-center">Terima</th>
                            <th class="text-center">Pakai</th>
                            @endforeach
                            <th class="text-center">Saldo Akhir</th>
                            <th class="text-center">Min</th>
                            <th class="text-center">Opname</th>
                            <th class="text-center">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $disabled = userCan('change-module-stock-opname') ? '' : 'disabled';
                        @endphp
                        @foreach ($modules as $module)
                        @php
                            $endStock = $additionTotal = $deductionTotal = 0
                        @endphp
                        <tr>
                            <td>{{ $module->code }}</td>
                            <td>{{ $module->initial_stock }}</td>
                            @foreach (range(1, 5) as $weekNo)
                            <td>{{ $module['addition_w' . $weekNo] }}</td>
                            <td>{{ $module['deduction_w' . $weekNo] }}</td>
                            @php
                                $additionTotal += $module['addition_w' . $weekNo];
                                $deductionTotal += $module['deduction_w' . $weekNo];
                                $endStock = $module->initial_stock + $additionTotal - $deductionTotal;
                                $currKey = 'modules.' . $module->id;
                                $diff = $module->opname - $endStock;
                            @endphp
                            @endforeach
                            <td>{{ $additionTotal }}</td>
                            <td>{{ $deductionTotal }}</td>
                            <td class="end-stock" data-value={{ $endStock}}>{{ $endStock }}</td>
                            <td>
                                {{ $module->min_stock }}<br />
                                <i class="icon wb-large-point {{ $endStock < $module->min_stock ? 'text-danger' : 'text-success' }}"></i>
                            </td>
                            <td>
                                <input type='text' class='form-control separator opname-input' value='{{ old("$currKey.opname", $module->opname) }}' required {{ $disabled }}>
                                <input type='hidden' name='modules[{{ $module->id }}][opname]' class='separator-hidden' value='{{ old("$currKey.opname", $module->opname) }}' required>
                            </td>
                            <td class="diff {{ $diff < 0 ? 'text-danger' : '' }}">{{ $diff }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.opname-input').keyup(function() {
                var opname = $(this).val()
                if (!opname) $(this).val(0).change()
                $('.update-button').removeAttr('disabled')
                var tr = $(this).closest('tr')

                var endStock = parseInt(tr.find('.end-stock').data('value') || 0)

                var diff = opname - endStock

                tr.find('.diff').removeClass('text-danger').text(diff.toLocaleString()).addClass(diff < 0 ? 'text-danger' : '')
            });
        });
    </script>
@endsection