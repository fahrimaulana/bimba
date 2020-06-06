@extends('backend.layouts.app')

@section('head')
    <title>Slip Progresif | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <style type="text/css">
        span.select2-selection.select2-selection--single {
            padding: 0 !important;
            height: 100% !important;
        }

        span#select2-staff-container {
            margin-left: 3% !important;
        }

        span.select2-selection__clear {
            margin-right: 15% !important;
        }
    </style>
@endsection

@section('title')
    Slip Progresif
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Slip Progresif</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a id="print" class="no-decor text-success btn btn-icon tl-tip btn-sm" data-original-title="Print Slip Progresif" data-toggle="modal" data-target="#reprint-receipt-modal"><i class="icon wb-print" aria-hidden="true"></i> Print</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2" align="center">
                    <img src="{{ asset('assets/images/logo-round.png') }}" class="w100">
                </div>
                <div class="col-md-10"><br>
                    <div class="col-md-12" align="center">
                        <h4>SLIP PEMBAYARAN PROGRESIF</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">No. Induk <span class="pull-right">:</span></div>
                                <div class="col-md-8" id="nik"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">Nama Staff <span class="pull-right">:</span></div>
                                <div class="col-md-8">
                                    <select class="select2" id="staff">
                                        @foreach ($staffs as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">Jabatan <span class="pull-right">:</span></div>
                                <div class="col-md-8" id="position"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">biMBA Unit <span class="pull-right">:</span></div>
                                <div class="col-md-8" id="department"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">Tgl Masuk <span class="pull-right">:</span></div>
                                <div class="col-md-8" id="joined_date"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">Bulan Bayar <span class="pull-right">:</span></div>
                                <div class="col-md-8" id="month_paid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12"><h5>a. Rincian Murid</h5></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid Aktif (AM 1)</div>
                        <div id="am1" class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid Aktif Yang Bayar SPP (AM 2)</div>
                        <div id="am2" class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid Garansi (MGRS)</div>
                        <div id="mgrs" class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid Dhuafa (MDF)</div>
                        <div id="mdf" class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid BNF (MBNF 1)</div>
                        <div id="mbnf1" class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid BNF Yang Bayar SPP (MBNF 2)</div>
                        <div id="mbnf2" class="col-md-4"></div>
                    </div>
                    @foreach ($departments as $department)
                    <div class="row">
                        <div class="col-md-8">Murid Baru @if ($loop->iteration == 1) biMBA-AIUEO (MB) @else English biMBA (MBE) @endif</div>
                        <div id="mb{{$loop->iteration}}" class="col-md-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">Murid Trial @if ($loop->iteration == 1) biMBA-AIUEO (MT) @else English biMBA (MTE) @endif</div>
                        <div id="mt{{$loop->iteration}}" class="col-md-4"></div>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12"><h5>c. Rincian Pembayaran</h5></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Total Seluruh FM</div>
                        <div id="fmTotal" class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Nilai Progresif</div>
                        <div id="progressiveValue" class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Total Komisi</div>
                        <div id="commissionTotal" class="col-md-6"></div>
                    </div>
                    @foreach ($departments as $department)
                    <div class="row">
                        <div class="col-md-6">&nbsp;&nbsp;&nbsp;Komisi @if ($loop->iteration == 1) MB biMBA-AIUEO @else MB English biMBA @endif</div>
                        <div id="commissionMb{{$loop->iteration}}" class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">&nbsp;&nbsp;&nbsp;Komisi @if ($loop->iteration == 1) MT biMBA-AIUEO @else MT English biMBA @endif</div>
                        <div id="commissionMt{{$loop->iteration}}" class="col-md-6"></div>
                    </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-6">&nbsp;&nbsp;&nbsp;Komisi Asisten KU</div>
                        <div id="commissionAsku" class="col-md-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Total Pendapatan</div>
                        <div id="incomeTotal" class="col-md-6"></div>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12"><h5>b. Rincian Murid</h5></div>
                    </div>
                    @foreach ($departments as $department)
                        <div class="row">
                            <div class="col-md-7">Penerimaan SPP @if ($loop->iteration == 1) biMBA-AIUEO @else English biMBA @endif</div>
                            <div id="moneyOrder{{$loop->iteration}}" class="col-md-5"><span class="pull-left">Rp.</span> 0</div>
                        </div>
                    @endforeach
                    <br>
                    <div class="row" align="center">
                        <div class="col-md-6">Yang Menyerahkan,</div>
                        <div class="col-md-6">Mengetahui,</div>
                    </div><br><br><br>
                    <div class="row" align="center">
                        <div class="col-md-6">_________________</div>
                        <div class="col-md-6">_________________</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12"><h5>d. Rincian Adjusment</h5></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Kelebihan Progresif</div>
                        <div class="col-md-6"><span class="pull-left">Rp.</span> 0</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Kekurangan Progresif</div>
                        <div class="col-md-6"><span class="pull-left">Rp.</span> 0</div>
                    </div><br>
                    <div class="row bg-info">
                        <div class="col-md-6">Jumlah Yang Dibayarkan</div>
                        <div id="paidOut" class="col-md-6"></div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-12">
                            <i>Ditransfer Ke :</i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <i><span id="account_bank"></span> | <span id="account_number"></span> | <span id="account_name"></span></i>
                        </div>
                    </div>
                </div>
            </div><hr>
            <div class="row">
                <div class="col-md-12">
                    <table id="datatable" class="table table-bordered table-hover table-striped w100" cellspacing="0">
                        <thead>
                            <tr>
                                <th align="center">NIM</th>
                                <th align="center">Nama Murid</th>
                                <th align="center">Kelas</th>
                                <th align="center">Gol</th>
                                <th align="center">KD</th>
                                <th align="center">SPP</th>
                                <th align="center">Status</th>
                                <th align="center">Note</th>
                                <th align="center">Cek</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div class="modal show" id="reprint-receipt-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title"> Slip Gaji</h4>
                </div>
                <div class="modal-body pad0" style="height: 500px">
                    @include ('inc.spinner')
                    <iframe id="pdf-embed" class="pdf-embed" src="" type="application/pdf" width="100%" height="500px"></iframe>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var tableRef = $('#datatable').DataTable();

            function addRow(nim, name, department, code, grade, spp, status, note) {
                tableRef.row.add([
                    nim,
                    name,
                    department,
                    code,
                    grade,
                    spp,
                    status,
                    note,
                    ' '
                ]).draw(false);
            }

            $('.select2').select2({
                'placeholder': 'Pilih salah satu',
                'allowClear': true,
                'width': '100%'
            });

            function currencyFormat(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            }

            function getData(id) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('client.progressive.slip.data') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('#nik').text(response.data.nik);
                            $('#position').text(response.data.position);
                            $('#department').text(response.data.department);
                            $('#joined_date').text(response.data.joined_date);
                            $('#month_paid').text(response.data.month_paid);

                            $('#account_bank').text(response.data.account_bank);
                            $('#account_number').text(response.data.account_number);
                            $('#account_name').text(response.data.account_name);

                            $('#am1').text(response.data.student_data.active);
                            $('#am2').text(response.data.student_data.active_paid.paid_count);
                            $('#mgrs').text(response.data.student_data.warranty);
                            $('#mdf').text(response.data.student_data.dhuafa);
                            $('#mbnf1').text(response.data.student_data.bnf);
                            $('#mbnf2').text(response.data.student_data.bnf_paid.paid_count);
                            @foreach ($departments as $department)
                                $('#mb{{$loop->iteration}}').text(response.data.student_data.department_{{$department->id}}.mb);
                                $('#mt{{$loop->iteration}}').text(response.data.student_data.department_{{$department->id}}.mt);
                            @endforeach
                            @foreach ($departments as $department)
                                $('#moneyOrder{{$loop->iteration}}').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.money_order.department_{{$department->id}}.paid_total));
                            @endforeach

                            $('#fmTotal').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+response.data.fm.total);
                            $('#progressiveValue').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.progressive));
                            $('#commissionTotal').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.commission.total));
                            @foreach ($departments as $department)
                                $('#commissionMb{{$loop->iteration}}').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.commission.department_{{$department->id}}.mb));
                                $('#commissionMt{{$loop->iteration}}').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.commission.department_{{$department->id}}.mt));
                            @endforeach
                            $('#commissionAsku').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.commission.asku));
                            $('#incomeTotal').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.paid_out));
                            $('#paidOut').html('<span class="pull-left">Rp.</span> '+currencyFormat(response.data.paid_out));

                            var dataStudents = response.data.data.students;
                            for (var i = 0; i < dataStudents.length; i++) {
                                addRow(dataStudents[i].nim, dataStudents[i].name, dataStudents[i].department, dataStudents[i].code, dataStudents[i].grade, dataStudents[i].spp, dataStudents[i].status, dataStudents[i].note);
                            }

                            var printUrl = '{{ route("client.progressive.slip.print", ":id") }}';
                            printUrl = printUrl.replace(':id', response.data.id);
                            $('#print').attr('data-url', printUrl);
                        } else {
                            // Code...
                        }
                    }
                });
            }

            getData($('#staff').val());
            $('#staff').change(function () {
                getData(this.value);
            });

            $('#reprint-receipt-modal').on('show.bs.modal', function(event) {
                $('#pdf-embed', this).attr('src', $(event.relatedTarget).data('url'));
            });
            $('#reprint-receipt-modal').on('hidden.bs.modal', function () {
                $('#pdf-embed', this).removeAttr('src');
            });
        });
    </script>
@endsection