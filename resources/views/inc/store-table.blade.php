<table class="table table-bordered table-hover table-striped" cellspacing="0" id="store-table">
    <thead>
        <tr>
            <th class="text-center">Code</th>
            <th class="text-center">{{ getStoreAlias() }}</th>
            <th class="text-center">{{ getStoreAlias() }} Category</th>
        </tr>
    </thead>
    <tbody>
    @foreach($stores as $store)
        <tr>
            <td align="center">{{ $store->code }}</td>
            <td align="center">{{ $store->name }}</td>
            <td align="center">{{ $store->category->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>