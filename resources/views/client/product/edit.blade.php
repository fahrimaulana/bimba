@extends('backend.layouts.app')

@section('head')
    <title>Ubah Produk | {{ env('APP_NAME') }}</title>
@endsection

@section('title')
    Ubah Produk
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.product.index') }}">Produk</a></li>
    <li class="breadcrumb-item active">Ubah Produk</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Produk</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class='row'>
                    <div class='form-group col-md-2'>
                        <label class='control-label'>Kode</label>
                        <input type='text' name='code' class='form-control' value='{{ old('code', $product->code) }}' required />
                    </div>
                    <div class='form-group col-md-5'>
                        <label class='control-label'>Nama</label>
                        <input type='text' name='name' class='form-control' value='{{ old('name', $product->name) }}' required />
                    </div>
                    <div class='form-group col-md-5'>
                        <label class='control-label'>Harga</label>
                        <input type='text' class='form-control separator currency' value='{{ old('price', $product->price) }}' required>
                        <input type='hidden' name='price' class='separator-hidden' value='{{ old('price', $product->price) }}' required>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.product.index') }}" class="btn btn-danger">Kembali</a>
                    <button type="Submit" class="btn btn-primary pull-right">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection