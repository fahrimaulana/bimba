<div class="form-group col-md-6">
    <label class="control-label">Client</label>
    <select name="client_id" class="form-control select2">
        <option value="{{ client()->id }}" {{ old('client_id') == client()->id ? 'selected' : '' }}>{{ client()->name }} ({{ client()->code }})</option>
    </select>
</div>