@extends('backend.layouts.app')

@section('head')
    <title>Laporan Kategori Keuangan | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/documents.css') }}">
    <link rel="stylesheet" href="{{ asset('global/fonts/material-design/material-design.css') }}">
@endsection

@section('title')
    Laporan Kategori Keuangan
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('client.report.view.index') }}">Report</a></li>
    <li class="breadcrumb-item active">Laporan Kategori Keuangan</li>
@endpush

@section('content')
    <div class="panel app-documents">
        <div class="panel-body">
            <div class="form-group">
                <div class="input-search input-search-dark">
                    <div class="input-group">
                        <button type="submit" class="input-search-btn">
                        <i class="icon wb-search" aria-hidden="true"></i>
                        </button>
                        <input type="text" class="form-control" placeholder="Search Report..." id="reportSearch">
                    </div>
                </div>
            </div>
            <div class="documents-wrap categories" data-plugin="animateList" data-child="li">
                <ul class="blocks blocks-100 blocks-xxl-4 blocks-lg-3 blocks-sm-2" data-plugin="matchHeight" id="reportList">
                    <li>
                        <div class="category">
                            <div class="icon-wrap">
                                <i class="icon wb-user" aria-hidden="true"></i>
                            </div>
                            <h4>Voucher</h4>
                            <p>ini mengetahui jumlah Voucher</p>
                            <a href="{{ route('client.report.keuangan.voucher.show-filter') }}">Lihat Daftar</a>
                        </div>
                    </li>
                    <li>
                        <div class="category">
                            <div class="icon-wrap">
                                <i class="icon wb-user" aria-hidden="true"></i>
                            </div>
                            <h4>Penerimaan</h4>
                            <p>ini mengetahui jumlah Penerimaan</p>
                            <a href="{{ route('client.report.keuangan.penerimaan.show-filter') }}">Lihat Daftar</a>
                        </div>
                    </li>
                    <li>
                        <div class="category">
                            <div class="icon-wrap">
                                <i class="icon wb-user" aria-hidden="true"></i>
                            </div>
                            <h4>Petty Cash</h4>
                            <p>ini mengetahui jumlah Petty Cash</p>
                            <a href="{{ route('client.report.keuangan.petty-cash.show-filter') }}">Lihat Daftar</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('assets/js/thirdparty/jquery.searchable-1.0.0.min.js') }}"></script>
    <script src="{{ asset('global/js/Plugin/matchheight.js') }}"></script>
    <script src="{{ asset('global/js/Plugin/animate-list.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#reportList').searchable({
                searchField: '#reportSearch',
                selector: 'li',
                childSelector: '.category',
                show: function(elem) {
                    elem.slideDown(100);
                },
                hide: function(elem) {
                    elem.slideUp(100);
                }
            });
        });
    </script>
@endsection