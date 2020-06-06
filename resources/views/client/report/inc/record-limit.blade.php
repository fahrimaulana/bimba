<div class="row">
    <div class="form-group col-sm-6">
        <label class="control-label">Order By </label>
        <select name="order_by" class="form-control select2" data-placeholder="Choose One">
            <option value="asc" {{ (old('order_by') == 'asc') ? 'selected' : '' }}>Ascending</option>
            <option value="desc" {{ (old('order_by') == 'desc') ? 'selected' : '' }}>Descending</option>
        </select>
    </div>
    <div class="form-group col-sm-6">
        <label class="control-label">Total Record </label>
        <input type="text" class="form-control separator" value="{{ old('limit') }}" placeholder="Limit Result">
        <input type="hidden" name="limit" class="separator-hidden" value="{{ old('limit') }}">
    </div>
</div>