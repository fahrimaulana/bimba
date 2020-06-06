<div class="row">
    <div class="form-group col-sm-12">
        <label class="control-label">Filter Tanggal</label>
        <div class="input-daterange datepicker">
            <div class="input-group">
                <span class="input-group-addon">Dari</span>
                <input type="text" class="form-control valueDate" name="from_date" value="{{ old('from_date', $from) }}" required>
            </div>
            <div class="input-group">
                <span class="input-group-addon">Untuk</span>
                <input type="text" class="form-control limit-value" name="to_date" value="{{ old('to_date', $to) }}" required>
            </div>
        </div>
    </div>
</div>