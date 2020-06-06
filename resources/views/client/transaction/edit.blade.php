@extends('backend.layouts.app')

@section('head')
    <title>Ubah Penerimaan | {{ env('APP_NAME') }}</title>
    <link rel='stylesheet' href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel='stylesheet' href='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css') }}'>
@endsection

@section('title')
    Ubah Penerimaan
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('client.transaction.index') }}">Penerimaan</a></li>
    <li class="breadcrumb-item active">Ubah Penerimaan</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            <h3>Ubah Penerimaan</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('client.transaction.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                @include('client.transaction.partial.setting1')
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/select2/select2.min.js') }}"></script>
    <script src='{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') }}'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var transactionDetailProducts = @json($transactionDetailProducts);
            var transactiontuition = @json($transactiontuition);

            @include('inc.datepicker')
            $('.disabled-input').attr('disabled', 'disabled');
            $('.select2').select2({
                'placeholder' : 'Pilih salah satu',
                'allowClear' : true,
                'width' : '100%'
            });

            var countProduct = 4;

            addProductFilter();
            initSettingData();
            $('.add-product').click(function(e) {
                addProductFilter();
            });

            function initSettingData()
            {
                transactionDetailProducts.forEach(function(item) {
                    addProductFilter(item);
                });
            }

            function addProductFilter(item=null) {
                var template = $('#product-template');
                var clone = template.clone().removeAttr('id').removeClass('iTemplate');
                $('.disabled-input', clone).attr('disabled', 'disabled');
                $('.product', clone).attr('name', 'settings'+ '[' + countProduct + '][product]');

                $('.product', clone).change(function() {
                    var price = $('.product option:selected', clone).data('product-price');
                    var countProduct1 = countProduct -1;

                    $('.product-qty', clone).removeAttr('disabled').attr('name', 'settings'+ '[' + countProduct1 + '][product_qty]');
                    $('.price', clone).val(price);
                    getSubTotalProduct(clone);
                });

                $('.product-qty', clone).change(function() {
                    getSubTotalProduct(clone);
                    return;
                });

                $('#select2', clone).select2({
                    'placeholder' : 'Pilih salah satu',
                    'allowClear' : true,
                    'width' : '100%'
                });

                if (countProduct != 4) {
                    $('.add-product', clone).addClass('iTemplate');
                    $('.delete-product', clone).removeClass('iTemplate');
                }

                $('.delete-product', clone).click(function() {
                    var grandTotalProduct = parseInt($('.product-grand-total').val() - $('.product-total', clone).val());
                    $('.product-grand-total').val(grandTotalProduct);
                    getGrandTotal();
                    $(this).parent().remove();
                });

                if (item) {
                    var grandTotal =0;
                    $('.product', clone).val(item.product_id).change();
                    $('.product-qty', clone).val(item.qty);
                    $('.total', clone).val(item.total);
                    $('.total').each(function() {
                        grandTotal += +$(this).val();
                    });
                    $('.grand-total').val(grandTotal);
                }
                template.before(clone);
                countProduct++;
            }

            function getSubTotalProduct(clone) {
                var grandTotalProduct = 0;
                var productQty = $('.product-qty', clone).val();
                $('.product-grand-total', clone).val(0);

                var price = $('.product option:selected', clone).data('product-price');
                total = parseInt(productQty * price);

                $('.product-total', clone).val(total);

                $('.product-total').each(function() {
                    grandTotalProduct += +$(this).val();
                });
                $('.product-grand-total').val(grandTotalProduct);
                getGrandTotal();
                return;
            }

            function getClassPriceData(studentId) {
                $.ajax({
                    url: '{{ url('unit') }}/master/class-price/'+studentId,
                    method: 'get',
                    statusCode: {
                        200: function(data) {
                            $('.tuition-price').val(data.price);
                            $('.tuition-total').val(data.price);
                            return;
                        },
                        422: function(data) {
                            swal("Error!", "Harga SPP tidak ditemukan","Error");
                            $('.tuition-price').val(0).change();
                            return
                        }
                    }
                });
            }

            function getVoucherData(studentId) {
                $.ajax({
                    url: '{{ url('unit') }}/finance/voucher/'+studentId,
                    method: 'get',
                    success: function(data) {
                        $('.voucher-data').remove();
                        $.each(data.data, function(i, data){
                            $('.tuition-voucher').append("<option value='"+data.id+"' data-voucher='"+data.value+"'>"+data.code+' (' +data.value+")</option>");
                        });
                        $('.tuition-voucher').select2({
                            'placeholder' : 'Pilih salah satu',
                            'allowClear' : true,
                            'width' : '100%'
                        }).change();
                    }
                });
            }

            if($('#student').find(':selected').val()) {
                getStudent();
            }
            $('#student').on('change', function() {
                getStudent();

                return;
            });

            if (transactiontuition) {
                $('#student').val(transactiontuition.student_id).change();
                $('.tuition-voucher').val(transactiontuition.transaction_detail.voucher_id);
                $('.tuition-month').val(transactiontuition.transaction_detail.qty);
                $('.tuition-total').val(transactiontuition.transaction_detail.total);
            }

            function getStudent() {
                $('.tuition-month').removeAttr('disabled');
                $('.tuition-voucher').removeAttr('disabled');
                $('.student-event').removeAttr('disabled');
                $('.student-other').removeAttr('disabled');
                $('#year-filter').removeAttr('disabled');
                $('#month-filter').removeAttr('disabled');

                var studentId = $('#student').find(':selected').data('student-id');
                var departmentPrice = $('#student').find(':selected').data('department-price');
                var departmentid = $('#student').find(':selected').data('department-id');
                $('#department-price').append('<option value="'+ departmentid +'" selected="selected">Rp '+departmentPrice+'</option>');

                getClassPriceData(studentId);
                getVoucherData(studentId);
                return ;
            }

            $('.registration').change(function() {
                getGrandTotal();

                return;
            });

            $('.student-other').change(function() {
                getGrandTotal();

                return;
            });

            $('.student-event').change(function() {
                getGrandTotal();
                return;
            });

            $('.tuition-voucher').change(function() {
                getSubTotalTuition();
                return;
            });

            $('.tuition-month').change(function() {
                getSubTotalTuition();
                return;
            });

            function getSubTotalTuition() {
                var price = parseInt($('.tuition-price').val());
                var month = parseInt($('.tuition-month').val());
                var voucher = parseInt($('.tuition-voucher').find(":selected").data('voucher'));
                if (!voucher) {
                    voucher = 0;
                }

                if (month < 0) {
                    swal("Error!", "Bayar SPP belum di isi");
                    $('.tuition-month').val(0);
                    return;
                }

                var total = (price * month) - voucher;
                $('.tuition-total').val(total);
                getGrandTotal();
                return;
            }

            function getGrandTotal() {
                var departmentPrice = parseInt($('.registration').val());
                if (!departmentPrice) {
                    departmentPrice = 0;
                }
                var event = parseInt($('.student-event').val());
                var other = parseInt($('.student-other').val());
                var grandTotalProduct = parseInt($('.product-grand-total').val());
                var grandTotalTuition = parseInt($('.tuition-total').val());

                var grandTotal = departmentPrice + grandTotalProduct + grandTotalTuition + event + other;
                $('.grand-total').val(grandTotal);
            }
        });
    </script>
@endsection