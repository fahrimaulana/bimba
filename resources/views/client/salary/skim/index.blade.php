@extends('backend.layouts.app')

@section('head')
    <title>SKIM Gaji | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href='{{ asset('global/vendor/select2/select2.css') }}'>
@endsection

@section('title')
    SKIM Gaji
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">SKIM Gaji</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            <form class="form-horizontal" action="{{ route('client.salary.skim.update-all') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="m-b-15 clearfix">
                            @can('create-skim')
                            <a id="add-btn" class="btn btn-primary white">
                                <i class="icon wb-plus" aria-hidden="true"></i> Tambah SKIM Baru
                            </a>
                            @endcan
                            @can('edit-skim')
                            <button type="submit" class="btn btn-primary btn-success pull-right update-button" disabled>
                                <i class="icon fa-floppy-o" aria-hidden="true"></i> Simpan Perubahan
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover table-striped w100">
                    <tr>
                        <th>Jabatan</th>
                        <th>Masa Kerja</th>
                        <th>Status</th>
                        <th>Gaji Pokok</th>
                        <th>Harian</th>
                        <th>Fungsional</th>
                        <th>Kesehatan</th>
                        <th>THP</th>
                        <th>Aksi</th>
                    </tr>
                    @foreach ($positionSalaries as $salary)
                        @php
                            $total = $salary->basic_salary + $salary->daily + $salary->functional + $salary->health;
                            $currKey = 'salaries.' . $salary->id;
                            $disabled = userCan('edit-skim') ? '' : 'disabled'
                        @endphp
                        <tr>
                            <td>{{ optional($salary->position)->name }}</td>
                            <td>{{ $salary->min_work_length }} - {{ $salary->max_work_length }} Bulan</td>
                            <td>{{ $salary->indoStatus }}</td>
                            <td>
                                <input type='text' class='form-control separator salary-input' value='{{ old("$currKey.basic_salary", $salary->basic_salary) }}' required {{ $disabled }}>
                                <input type='hidden' name='salaries[{{ $salary->id }}][basic_salary]' class='basic-salary separator-hidden' value='{{ old("$currKey.basic_salary", $salary->basic_salary) }}' required>
                            </td>
                            <td>
                                <input type='text' class='form-control separator salary-input' value='{{ old("$currKey.daily", $salary->daily) }}' required {{ $disabled }}>
                                <input type='hidden' name='salaries[{{ $salary->id }}][daily]' class='daily separator-hidden' value='{{ old("$currKey.daily", $salary->daily) }}' required>
                            </td>
                            <td>
                                <input type='text' class='form-control separator salary-input' value='{{ old("$currKey.functional", $salary->functional) }}' required {{ $disabled }}>
                                <input type='hidden' name='salaries[{{ $salary->id }}][functional]' class='functional separator-hidden' value='{{ old("$currKey.functional", $salary->functional) }}' required>
                            </td>
                            <td>
                                <input type='text' class='form-control separator salary-input' value='{{ old("$currKey.health", $salary->health) }}' required {{ $disabled }}>
                                <input type='hidden' name='salaries[{{ $salary->id }}][health]' class='health separator-hidden' value='{{ old("$currKey.health", $salary->health) }}' required>
                            </td>
                            <td class="total">{{ thousandSeparator($total) }}</td>
                            <td>
                                @can ('delete-skim')
                                <a class="btn btn-sm btn-icon text-danger tl-tip" data-href="{{ route('client.salary.skim.destroy', $salary->id) }}" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus dari SKIM">
                                    <i class="icon wb-trash" aria-hidden="true"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </table>
            </form>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Tambah SKIM Gaji Baru</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.salary.skim.store') }}" method="POST">
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Jabatan</label>
                        <select class='form-control select2' name='position_id' required>
                            <option></option>
                            @foreach ($positions as $position)
                            <option value='{{ $position->id }}' {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-2'>
                        <label class='control-label'>Masa Kerja Minimal</label>
                        <input type='text' name='min_work_length' class='form-control' value='{{ old('min_work_length', 0) }}' required />
                    </div>
                    <div class='form-group col-md-2'>
                        <label class='control-label'>Masa Kerja Maksimal</label>
                        <input type='text' name='max_work_length' class='form-control' value='{{ old('max_work_length', 24) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Status</label>
                        <select class='form-control select2' name='status' required>
                            <option></option>
                            @foreach ($staffStatuses as $status)
                            <option value='{{ $status }}' {{ old('status') == $status ? 'selected' : '' }}>
                                @if ($status == \App\Enum\Staff\StaffStatus::Active)
                                    Aktif
                                @elseif ($status == \App\Enum\Staff\StaffStatus::Intern)
                                    Magang
                                @else
                                    {{ $status }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label type-label'>Gaji Pokok</label>
                        <input type='text' class='form-control separator' value='{{ old('basic_salary') }}' required>
                        <input type='hidden' name='basic_salary' class='separator-hidden' value='{{ old('basic_salary') }}' required>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label type-label'>Harian</label>
                        <input type='text' class='form-control separator' value='{{ old('daily') }}' required>
                        <input type='hidden' name='daily' class='separator-hidden' value='{{ old('daily') }}' required>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label type-label'>Fungsional</label>
                        <input type='text' class='form-control separator' value='{{ old('functional') }}' required>
                        <input type='hidden' name='functional' class='separator-hidden' value='{{ old('functional') }}' required>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label type-label'>Kesehatan</label>
                        <input type='text' class='form-control separator' value='{{ old('health') }}' required>
                        <input type='hidden' name='health' class='separator-hidden' value='{{ old('health') }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')
    <script src='{{ asset('global/vendor/select2/select2.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tl-tip').tooltip();

            $('.select2').select2({
                'placeholder': 'Pilih salah satu',
                'allowClear': true,
                'width': '100%'
            });

            $('#add-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#add-form').offset().top - 100
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100
                }, "slow");
            });

            $('.salary-input').keyup(function() {
                if (!$(this).val()) $(this).val(0).change()
                $('.update-button').removeAttr('disabled')
                var tr = $(this).closest('tr')

                var basicSalary = parseInt(tr.find('.basic-salary').val() || 0)
                var daily = parseInt(tr.find('.daily').val() || 0)
                var functional = parseInt(tr.find('.functional').val() || 0)
                var health = parseInt(tr.find('.health').val() || 0)

                var total = basicSalary + daily + functional + health

                tr.find('.total').text(total.toLocaleString())
            });
        });
    </script>
@endsection