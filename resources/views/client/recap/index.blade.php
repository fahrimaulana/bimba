@extends('backend.layouts.app')

@section('head')
    <title>Rekap | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
@endsection

@section('title')
    Rekap
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Rekap</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <h4 class="text-center">REKAPITULASI</h4>
                <div class="form-group col-md-12">
                    <label class="control-label">Periode Tanggal</label>
                    <div class="input-daterange datepicker pad0">
                        <div class="input-group">
                            <span class="input-group-addon">Dari</span>
                            <input type="text" class="form-control" name="from" value="{{ old('from', $from) }}" required id="filter-date-periode-from">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon">Untuk</span>
                            <input type="text" class="form-control" name="to" value="{{ old('to', $to) }}" required id="filter-date-periode-to">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">a. Transaksi Penerimaan</th>
                        </tr>
                        <tr>
                            <td>401 Daftar</td>
                            <td>{{ thousandSeparator($receiptTransaction->daftar, 2) }}</td>
                        </tr>
                        <tr>
                            <td>402 Voucher</td>
                            <td>{{ thousandSeparator($receiptTransaction->voucher, 2) }}</td>
                        </tr>
                        <tr>
                            <td>403 SPP</td>
                            <td>{{ thousandSeparator($receiptTransaction->spp, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table" style="background: #f5dada;">
                                    <tr>
                                        <td width="5%"></td>
                                        <td width="65%">Cash</td>
                                        <td width="30%">{{ thousandSeparator($receiptTransaction->cash, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>Transfer</td>
                                        <td>{{ thousandSeparator($receiptTransaction->transfer, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>EDC</td>
                                        <td>{{ thousandSeparator($receiptTransaction->edc, 2) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>404 Kaos Anak</td>
                            <td>{{ thousandSeparator($receiptTransaction->ka, 2) }}</td>
                        </tr>
                        <tr>
                            <td>405 Modul Eksklusif</td>
                            <td>{{ thousandSeparator($receiptTransaction->me, 2) }}</td>
                        </tr>
                        <tr>
                            <td>406 Sertifikat</td>
                            <td>{{ thousandSeparator($receiptTransaction->sertifikat, 2) }}</td>
                        </tr>
                        <tr>
                            <td>407 Surat Tanda Peserta biMBA</td>
                            <td>{{ thousandSeparator($receiptTransaction->STPb, 2) }}</td>
                        </tr>
                        <tr>
                            <td>408 Tas biMBA</td>
                            <td>{{ thousandSeparator($receiptTransaction->tas, 2) }}</td>
                        </tr>
                        <tr>
                            <td>409 Event</td>
                            <td>{{ thousandSeparator($receiptTransaction->event, 2) }}</td>
                        </tr>
                        <tr>
                            <td>410 Lain-lain</td>
                            <td>{{ thousandSeparator($receiptTransaction->other, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{ thousandSeparator($totalTransaction->total, 2) }}</td>
                        </tr>
                    </table>
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">Detail Total</th>
                        </tr>
                        @foreach($totalTransactionDetailMethods as $totalTransactionDetailMethod)
                            <tr>
                                <td>{{ $totalTransactionDetailMethod->payment_method }}</td>
                                <td>{{ thousandSeparator($totalTransactionDetailMethod->payment_method_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-12 col-lg-6">
                    <table class="table table-bordered table-hover table-striped w100">
                        <tr>
                            <th colspan="2" class="text-center">b. Transaksi Petty Cash</th>
                        </tr>
                        <tr>
                            <td>Saldo Awal</td>
                            <td>{{ thousandSeparator($initialSaldo, 2) }}</td>
                        </tr>
                        <tr>
                            <td>500 Petty Cash</td>
                            <td>{{ $totalPettyCash ? thousandSeparator($totalPettyCash, 2) : 0 }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Pengeluaran</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table" style="background: #f5dada;">
                                @php $i=501 @endphp
                                @foreach($pettyCashCategories as $category)
                                    <tr>
                                        <td>{{ $i .' '. $category->name }}</td>
                                        <td>
                                            @if (json_decode($spendingPettyCashs->where('id', $category->id)))
                                                @foreach ($spendingPettyCashs->where('id', $category->id) as $spendingPettyCash)
                                                    {{ thousandSeparator($spendingPettyCash->total_spending, 2) }}
                                                @endforeach
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @php $i++ @endphp
                                @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>{{ thousandSeparator($totalSpending->total_spending, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Saldo Akhir</td>
                            <td>{{ thousandSeparator($endingSaldo, 2) }}</td>
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
    <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @include('inc.datepicker')

            $('#filter-date-periode-to').on('change', function() {
                getFilterData();

                return;
            });

            $('#filter-date-periode-from').on('change', function() {
                getFilterData();

                return;
            });

            function getFilterData() {
                var formDate = $('#filter-date-periode-from').val();
                var toDate = $('#filter-date-periode-to').val();
                window.location.assign('{{ url('unit') }}/finance/recap/' + formDate +'/' + toDate);
            }
        });
    </script>
@endsection