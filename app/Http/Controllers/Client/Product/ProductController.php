<?php

namespace App\Http\Controllers\Client\Product;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-product-list');

        return view('client.product.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-product');

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $product = new Product;
        $product->client_id = clientId();
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();

        return redirect()->route('client.product.index')->with('notif_success', 'Produk telah berhasil ditambahkan!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-product');

        $product = Product::findOrFail($id);
        return view('client.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-product');

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $product = Product::findOrFail($id);
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();

        return redirect()->route('client.product.index')->with('notif_success', 'Produk telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-product');

        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('client.product.index')->with('notif_success', 'Produk telah berhasil dihapus!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-product-list');

        $products = Product::query();

        return Datatables::of($products)
                    ->addColumn('action', function($product) {
                        $edit = '<a href="' . route('client.product.edit', $product->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah produk"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.product.destroy', $product->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus produk"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-product') ? $edit : '') . (userCan('delete-product') ? $delete : '');
                    })
                    ->make(true);
    }
}