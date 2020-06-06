            <div class="panel-heading">
                <h3 class="panel-title">Penerimaan</h3>
            </div>
            <div class="panel-body">
                @include('inc.error-list')
                    <div class='row'>
                        <div class='form-group col-md-6'>
                            <label class='control-label'>Murid</label>
                            <select class='form-control select2' name='student_id' id="student" required>
                                <option></option>
                                @foreach ($students as $student)
                                <option value='{{ $student->id }}' {{ old('student_id') == $student->id ? 'selected' : '' }} data-student-id="{{ $student->id }}">{{ $student->nim .' - '. $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='form-group col-md-6'>
                            <label class='control-label'>Daftar</label>
                            <select class='form-control select2 registration' name="settings[0][registration]">
                                <option></option>
                                @foreach ($departments as $department)
                                <option value='{{ $department->price }}' {{ old('settings[0][registration]') == $department->id ? 'selected' : '' }} data-department-id="{{ $department->id }}">{{ thousandSeparator($department->price) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">Bayar SPP untuk:</label>
                            <div class="input-group">
                                <input type="number" name="tuition_month" class="form-control disabled-input tuition-month" min="0" value="{{ old('tuition_month', isset($transactionDetailqty) ? $transactionDetail->qty : '' ) }}" required>
                                <input type="hidden" name="settings[1][tuition_price]" class="tuition-price">
                                <span class="input-group-addon">Bulan</span>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="control-label">Mulai dari Bulan</label>
                            <select id="year-filter" name="year" class="show-tick select-user-access " data-plugin="selectpicker">
                                @foreach (range(2010, now()->year) as $year)
                                    <option value="{{ $year }}" {{ (int) year() === $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2" style="margin-top: 28px;">
                            <select id="month-filter" name="month" class="show-tick select-user-access" data-plugin="selectpicker">
                                @foreach (shortMonths() as $monthNo => $monthName)
                                    <option value="{{ $monthNo }}" {{ (int) month() === $monthNo ? 'selected' : '' }}>{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Biaya SPP</label>
                            <div class="input-group">
                                <input type="text" class="form-control separator disabled-input tuition-price divide" value="1">
                                <span class="input-group-addon">Per Bulan</span>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="control-label">Total</label>
                            <input type="text" name="tuition_total" class="form-control separator disabled-input tuition-total" value="0">
                        </div>
                    </div>
                    <div class='row'>
                        <div class='form-group col-md-4'>
                            <label class='control-label'>Voucher</label>
                            <select name='voucher_id' class='form-control select2 disabled-input tuition-voucher' value="voucher_id">
                                <option></option>
                            </select>
                        </div>
                        <div class='form-group col-md-4'>
                            <label class='control-label'>Event</label>
                            <input type='text' name='settings[2][event]' class='divide form-control separator disabled-input student-event' min="0" value="{{ old('settings[2][event]', isset($transactionDetailEvent) ? $transactionDetailEvent->total : '0') }}"  >
                            <input type="hidden" name="settings[2][event]" class="separator-hidden" min="0" value="{{ old('settings[2][event]', isset($transactionDetailEvent) ? $transactionDetailEvent->total : '0') }}" >
                        </div>
                        <div class='form-group col-md-4'>
                            <label class='control-label'>Lain-Lain</label>
                            <input type='text' name='settings[3][other]' class='form-control separator disabled-input student-other' min="o" value="{{ old('settings[3][other]', isset($transactionDetailOther) ? $transactionDetailOther->total : '0') }}" >
                            <input type="hidden" name="settings[3][other]" class="separator-hidden" min="0" value="{{ old('settings[3][other]', isset($transactionDetailOther) ? $transactionDetailOther->total : '0') }}" >
                        </div>
                    </div>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">Product</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        Product
                    </div>
                    <div class="col-md-2">
                        Jumlah
                    </div>
                    <div class="col-md-2">
                        Harga
                    </div>
                    <div class="col-md-2">
                        Total
                    </div>
                </div>
                <div class="row iTemplate" id="product-template">
                    <div class="form-group col-md-4">
                        <select class="form-control product" id="select2">
                            <option></option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }} data-product-id="{{ $product->id }}" data-product-price="{{ $product->price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <input type="number" class="form-control disabled-input product-qty" value="{{ old('product_qty', '1') }}" min="1" placeholder="0">
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control separator disabled-input price" min="0" placeholder="0">
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control separator disabled-input product-total" min="0" placeholder="0">
                        <input type="hidden" class="separator-hidden" min="0" placeholder="0">
                    </div>
                    <div class="form-group col-sm-2 btn btn-primary add-product"> Tambah</div>
                    <div class="form-group col-sm-2 btn btn-danger delete-product iTemplate"> Hapus</div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5>Total Keseluruhan Product</h5>
                    </div>
                    <div class="col-md-2">
                        <input type="text"  class="form-control separator disabled-input product-grand-total" placeholder="0" value="0">
                    </div>
                </div>
                <div class="row" style="margin-top: 24px;">
                    <div class="col-md-4">
                        <h5>Total Keseluruhan</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control separator disabled-input grand-total" placeholder="0">
                    </div>
                </div>
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">Metode Pembayaran</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Metode Pembayaran</label>
                        <select class="form-control select2" name="payment_method" required>
                            <option></option>
                            <option value="Cash" @if ($transaction->payment_method == 'Cash') selected @endif>Cash</option>
                            <option value="Edc" @if ($transaction->payment_method == 'Edc') selected @endif>Edc</option>
                            <option value="Transfer" @if ($transaction->payment_method == 'Transfer') selected @endif>Transfer</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Kwitansi No</label>
                        <input type="text" name="receipt_no" class="form-control" value="{{ old('receipt_no', isset($transaction) ? $transaction->receipt_no : '') }}" >
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Tanggal</label>
                        <div class='input-group'>
                            <span class='input-group-addon'><i class='icon wb-calendar'></i></span>
                            <input type='text' class='form-control datepicker' name='joined_date' value='{{ old('joined_date', now()->format('d-m-Y')) }}' required />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('client.transaction.index') }}" class="btn btn-danger">Batal</a>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </div>