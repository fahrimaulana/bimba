@extends('backend.layouts.app')

@section('head')
    <title>Rekap Progressive | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Rekap Progressive
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Rekap</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-hover table-striped w100" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="12">Rekap Progresif</th>
                            <th class="text-center" colspan="10">Data Murid</th>
                            <th class="text-center" colspan="{{ $grades->count() + 1 }}">SPP biMBA</th>
                            <th class="text-center" colspan="{{ $grades->count() }}">SPP English</th>
                            <th class="text-center" colspan="{{ $grades->count() + 3 }}">FM biMBA</th>
                            <th class="text-center" colspan="{{ $grades->count() }}">FM English</th>
                            <th class="text-center" colspan="5">Komisi</th>
                        </tr>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Departemen</th>
                            <th class="text-center">Masa Kerja</th>
                            <th class="text-center">SPP biMBA</th>
                            <th class="text-center">SPP English</th>
                            <th class="text-center">Total FM</th>
                            <th class="text-center">Progresif</th>
                            <th class="text-center">Komisi</th>
                            <th class="text-center">Dibayarkan</th>
                            <th class="text-center">AM1</th>
                            <th class="text-center">AM2</th>
                            <th class="text-center">MGRS</th>
                            <th class="text-center">MDF</th>
                            <th class="text-center">BNF</th>
                            <th class="text-center">BNF2</th>
                            @foreach ($departments as $department)
                                <th class="text-center">@if ($department->name == 'English biMBA') MB.Eb @else MB @endif</th>
                                <th class="text-center">@if ($department->name == 'English biMBA') MT.Eb @else MT @endif</th>
                            @endforeach
                            @foreach ($grades as $grade)
                                <th class="text-center">{{ $grade->name }}</th>
                            @endforeach
                            <th class="text-center">BNF</th>
                            @foreach ($grades as $grade)
                                <th class="text-center">{{ $grade->name }}</th>
                            @endforeach
                            @foreach ($grades as $grade)
                                <th class="text-center">{{ $grade->name }}</th>
                            @endforeach
                            <th class="text-center">MDF</th>
                            <th class="text-center">BNF</th>
                            <th class="text-center">MGRS2</th>
                            @foreach ($grades as $grade)
                                <th class="text-center">{{ $grade->name }}</th>
                            @endforeach
                            @foreach ($departments as $department)
                                <th class="text-center">@if ($department->name == 'English biMBA') MB.Eb @else MB @endif</th>
                                <th class="text-center">@if ($department->name == 'English biMBA') MT.Eb @else MT @endif</th>
                            @endforeach
                            <th class="text-center">ASKU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $result['name'] }}</td>
                                <td class="text-center">{{ $result['position'] }}</td>
                                <td class="text-center">{{ $result['status'] }}</td>
                                <td class="text-center">{{ $result['department'] }}</td>
                                <td class="text-center">{{ $result['joined_date'] }}</td>
                                @foreach ($departments as $department)
                                    <td class="text-center">{{ thousandSeparator($result['money_order']['department_'.$department->id]['paid_total']) }}</td>
                                @endforeach
                                <td class="text-center">{{ $result['fm']['total'] }}</td>
                                <td class="text-center">{{ thousandSeparator($result['progressive']) }}</td>
                                <td class="text-center">{{ thousandSeparator($result['commission']['total']) }}</td>
                                <td class="text-center">{{ thousandSeparator($result['paid_out']) }}</td>
                                <td class="text-center">{{ $result['student_data']['active'] }}</td>
                                <td class="text-center">{{ $result['student_data']['active_paid']['paid_count'] }}</td>
                                <td class="text-center">{{ $result['student_data']['warranty'] }}</td>
                                <td class="text-center">{{ $result['student_data']['dhuafa'] }}</td>
                                <td class="text-center">{{ $result['student_data']['bnf'] }}</td>
                                <td class="text-center">{{ $result['student_data']['bnf_paid']['paid_count'] }}</td>
                                @foreach ($departments as $department)
                                    <td class="text-center">{{ $result['student_data']['department_'.$department->id]['mb'] }}</td>
                                    <td class="text-center">{{ $result['student_data']['department_'.$department->id]['mt'] }}</td>
                                @endforeach
                                @foreach ($departments as $department)
                                    @foreach ($grades as $grade)
                                        <td class="text-center">{{ thousandSeparator($result['money_order']['department_'.$department->id]['grade_'.$grade->id]['paid_total']) }}</td>
                                    @endforeach
                                    @if (!$loop->last)
                                    <td class="text-center">{{ thousandSeparator($result['money_order']['bnf']['paid_total']) }}</td>
                                    @endif
                                @endforeach
                                @foreach ($departments as $department)
                                    @foreach ($grades as $grade)
                                        <td class="text-center">{{ $result['fm']['department_'.$department->id]['grade_'.$grade->id] }}</td>
                                    @endforeach
                                    @if (!$loop->last)
                                        <td class="text-center">{{ $result['student_data']['dhuafa'] }}</td>
                                        <td class="text-center">{{ $result['student_data']['bnf'] }}</td>
                                        <td class="text-center">{{ $result['student_data']['warranty'] }}</td>
                                    @endif
                                @endforeach
                                @foreach ($departments as $department)
                                    <td class="text-center">{{ thousandSeparator($result['commission']['department_'.$department->id]['mb']) }}</td>
                                    <td class="text-center">{{ thousandSeparator($result['commission']['department_'.$department->id]['mt']) }}</td>
                                @endforeach
                                <td class="text-center">{{ thousandSeparator($result['commission']['asku']) }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                columnDefs: [{
                    "paging":   false,
                    orderable: false
                }],
            });
        });
    </script>
@endsection