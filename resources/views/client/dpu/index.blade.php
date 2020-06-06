@extends('backend.layouts.app')

@section('head')
    <title>DPU | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    DPU
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">DPU</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            <h4 class="text-center">Data Perkembangan Unit</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="m-b-15 clearfix">
                        <form id="form" class="pull-right" method="GET" action="{{ URL::current() }}">
                            <select name="department_id" class="department-switch show-tick" data-plugin="selectpicker">
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ $department->id == $departmentId ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-hover table-striped w100">
                <tr>
                    <th>Tgl</th>
                    <th>BL</th>
                    @foreach (range(1, count($stats)) as $dayNo)
                    <th>{{ $dayNo }}</th>
                    @endforeach
                    <th>T</th>
                </tr>
                @foreach (['MTB' => 'trial_count', 'MB' => 'new_count', 'MK' => 'out_count', 'MA' => 'all_count', 'BNF' => 'bnf_count', 'D' => 'dhuafa_count'] as $statCode => $statColumn)
                @php $total = 0 @endphp
                <tr>
                    <td>{{ $statCode }}</td>
                    @if ($statCode === "MA")
                        <td>{{ $initialStudentCount }}</td>
                    @elseif ($statCode === "BNF")
                        <td>{{ $initialBNFCount }}</td>
                    @elseif ($statCode === "D")
                        <td>{{ $initialDhuafaCount }}</td>
                    @else
                        <td>-</td>
                    @endif
                    @foreach ($stats as $stat)
                    @php
                        $total += $stat->$statColumn
                    @endphp
                    <td>{{ $stat->$statColumn }}</td>
                    @endforeach
                    @if (!in_array($statCode, ["MA", "BNF", "D"]))
                        <td>{{ $total }}</td>
                    @else
                        <td>-</td>
                    @endif
                </tr>
                @endforeach
            </table>


            <div class="row">
                <div class="col-xxl-12 col-lg-12 col-xs-12">
                    <div class="card card-shadow card-responsive">
                        {!! $dpuChart->container() !!}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    {!! $dpuChart->script() !!}
    <script type="text/javascript">
        $(document).ready(function() {
            $('.department-switch').change(function() {
                $('#form').submit();
            })
        });
    </script>
@endsection