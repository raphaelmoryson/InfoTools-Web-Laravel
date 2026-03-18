<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index()
    {
        return Product::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        return response()->json(Product::create($data), 201);
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, $id) // On reçoit l'ID brut
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product);
    }

    public function destroy($id) // On reçoit l'ID brut
    {
        $product = Product::findOrFail($id);
        $product->delete();
    
        return response()->json(null, 204);
    }
}
