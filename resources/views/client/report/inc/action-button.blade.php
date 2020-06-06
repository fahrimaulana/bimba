<div class="row">
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="form-group col-sm-6">
                <button type="submit" class="btn btn-primary w100"><i class="fa fa-file-text-o"></i> Lihat Di PDF</button>
            </div>
            <div class="form-group col-sm-6">
                <button type="submit" formaction="{{ route($routeName. '.download-pdf') }}" class="btn btn-danger w100"><i class="fa fa-file-pdf-o"></i> Download Di  PDF</button>
            </div>
            <div class="form-group col-sm-6">
                <button type="submit" formaction="{{ route($routeName. '.download-excel') }}" class="btn btn-success w100"><i class="fa fa-file-excel-o"></i> Download Di Excel</button>
            </div>
            <div class="form-group col-sm-6">
                <button type="submit" formaction="{{ route($routeName. '.download-csv') }}" class="btn btn-info w100"><i class="fa fa-file-excel-o"></i> Download Di CSV</button>
            </div>
        </div>
    </div>
</div>