@extends('backend.layouts.app')

@section('head')
    <title>Ubah Profil Unit | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
@endsection

@section('title')
    Ubah Profil Unit
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Ubah Profil Unit</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Profil Unit</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>No Cabang</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code', $client->code) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>biMBA Unit</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $client->name) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Staff SOS</label>
                        <input type='text' name='staff_name' class='form-control' value='{{ old('staff_name', $client->staff_name) }}' required />
                    </div>
                </div>

                <h3>Kontak</h3>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Telp/HP</label>
                        <input type='text' name='phone' class='form-control' value='{{ old('phone', $client->phone) }}' required />
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Email</label>
                        <input type='text' name='email' class='form-control' value='{{ old('email', $client->email) }}' required />
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-12'>
                        <label class='control-label'>Alamat</label>
                        <textarea class='form-control' name='address' required>{{ old('address', $client->address->address) }}</textarea>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-3'>
                        <label class='control-label'>RT</label>
                        <input type='text' name='rt' class='form-control' value='{{ old('rt', $client->address->rt) }}' required/>
                    </div>
                    <div class='form-group col-md-3'>
                        <label class='control-label'>RW</label>
                        <input type='text' name='rw' class='form-control' value='{{ old('rw', $client->address->rw) }}' required/>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kode Pos</label>
                        <input type='text' name='pos_code' class='form-control' value='{{ old('pos_code', $client->address->pos_code) }}' required/>
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Provinsi</label>
                        <select name="province" class="form-control select2" id="province">
                        @foreach ($provinces as $province)
                            @if (old('province'))
                                <option class="province-data" value="{{$province->id}}" {{ old('province') == $province->id ? 'selected' : '' }}>{{$province->name}}</option>
                            @else
                                <option class="province-data" value="{{$province->id}}" {{ $province->id == optional($client->address)->province ? 'selected' : '' }}>{{$province->name}}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kota/Kab</label>
                        <select name="city" class="form-control select2" id="city">
                            @foreach ($cities as $city)
                                @if (old('city'))
                                    <option class="city-data" value="{{$city->id}}" {{ old('city') == $city->id ? 'selected' : '' }}>{{$city->name}}</option>
                                @else
                                    <option class="city-data" value="{{$city->id}}" {{ $city->id == optional($client->address)->city ? 'selected' : '' }}>{{$city->name}}</option>
                                @endif
                            @endforeach
                     </select>
                    </div>
                </div>
                <div class="row">
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kecamatan</label>
                        <select name="district" class="form-control select2" id="district">
                                @if (old('district'))
                                    <option class="district-data" value="{{optional($district)->id}}" {{ old('district') == optional($district)->id ? 'selected' : '' }}>{{$optional($district)->name}}</option>
                                @else
                                    <option class="district-data" value="{{optional($district)->id}}" {{ optional($district)->id == optional(optional($district)->address)->city ? 'selected' : '' }}>{{optional($district)->name}}</option>
                                @endif
                        </select>
                    </div>
                    <div class='form-group col-md-6'>
                        <label class='control-label'>Kel/Desa</label>
                        <select name="vilage" class="form-control select2" id="vilage">
                                @if (old('vilage'))
                                    <option class="vilage-data" value="{{optional($vilage)->id}}" {{ old('vilage') == optional($vilage)->id ? 'selected' : '' }}>{{optional($vilage)->name}}</option>
                                @else
                                    <option class="vilage-data" value="{{optional($vilage)->id}}" {{ optional($vilage)->id == optional(optional($vilage)->address)->city ? 'selected' : '' }}>{{optional($vilage)->name}}</option>
                                @endif
                        </select>
                    </div>
                </div>
                <h3>Rekening Bank</h3>
                <div class='row'>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nama Bank</label>
                        <input type='text' name='account_bank' class='form-control' value='{{ old('account_bank', $client->account_bank) }}'  />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Nomor Rekening</label>
                        <input type='text' name='account_number' class='form-control' value='{{ old('account_number', $client->account_number) }}' required />
                    </div>
                    <div class='form-group col-md-4'>
                        <label class='control-label'>Atas Nama</label>
                        <input type='text' name='account_name' class='form-control' value='{{ old('account_name', $client->account_name) }}' required />
                    </div>
                </div>
                <div class="form-group">
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });

            getCity();
            $('#province').on('change', function(){
                getCity();
                return;
            });

            $('#province').change();

            function getCity() {
                var provinceId = $('#province').val();
                if (provinceId == "") $('.city-data').remove();
                else {
                    $.ajax({
                        url: '{{ url('/api/v1/province/') }}/'+ provinceId + '/city',
                        success: function(data) {
                            $('.city-data').remove();
                            $.each(data.data, function(i, data){
                                $('#city').append($('<option>', {value: data.id, text: data.name}).addClass('city-data'));
                            });
                            $('#city').select2({
                                'placeholder' : 'Choose One',
                                'allowClear' : true,
                                'width' : '100%'
                            }).change();
                        }
                    });
                }

                return;
            }


            getDistrict();
            $('#city').on('change', function(){
                getDistrict();
                return;
            });

            $('#city').change();

            function getDistrict() {
                var cityId = $('#city').val();
                if (cityId == "") $('.distict-data').remove();
                else {
                    $.ajax({
                        url: '{{ url('/api/v1/city/') }}/'+ cityId + '/district',
                        success: function(data) {
                            $('.distict-data').remove();
                            $.each(data.data, function(i, data){
                                $('#district').append($('<option>', {value: data.id, text: data.name}).addClass('distict-data'));
                            });
                            $('#district').select2({
                                'placeholder' : 'Choose One',
                                'allowClear' : true,
                                'width' : '100%'
                            }).change();
                        }
                    });
                }

                return;
            }

            getVilage();
            $('#district').on('change', function(){
                getVilage();
                return;
            });
            $('#district').change();

            function getVilage() {
                var districtId = $('#district').val();
                if (districtId == "") $('.vilage-data').remove();
                else {
                    $.ajax({
                        url: '{{ url('/api/v1/district/') }}/'+ districtId + '/vilage',
                        success: function(data) {
                            $('.vilage-data').remove();
                            $.each(data.data, function(i, data){
                                $('#vilage').append($('<option>', {value: data.id, text: data.name}).addClass('vilage-data'));
                            });
                            $('#vilage').select2({
                                'placeholder' : 'Choose One',
                                'allowClear' : true,
                                'width' : '100%'
                            }).change();
                        }
                    });
                }

                return;
            }
        });
    </script>
@endsection