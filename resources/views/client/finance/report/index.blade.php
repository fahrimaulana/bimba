@extends('backend.layouts.app')
@section('head')
    <title>Laporan Keuangan | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a id="print" class="no-decor text-success btn btn-icon tl-tip btn-sm" data-original-title="Print Slip Progresif" data-toggle="modal" data-target="#reprint-receipt-modal"><i class="icon wb-print" aria-hidden="true"></i> Print</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12" align="center">
                        <h4>SUMMARY KEUANGAN</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <h4>Penerimaan</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Daftar <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['transaction_registration'] }}" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">SPP + VHB <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Penjualan <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Total <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['total_transaction'] }}" disabled></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <h4>Petty Cash</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Saldo Awal <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['petty_cash']['initial_saldo'] }}" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Debit <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['petty_cash']['debit'] }}" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Kredit <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['petty_cash']['credit'] }}" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Saldo Akhir <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['petty_cash']['final_saldo'] }}" disabled></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <h4>SPP Murid</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">SPP biMBA <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">SPP English <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Bagi Hasil biMBA <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Bagi Hasil English <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12" align="center">
                                    <h4>Lain-lain</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Penyerahan VHB <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Pemakaian VHB <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Gaji Staff <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['staff_salary'] }}" disabled></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">Progressive <span class="pull-right">:</span></div>
                                <div class="col-md-6 form-group"><input class="form-control input-sm" type="text" value="{{ $result['progressive'] }}" disabled></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
@endsection