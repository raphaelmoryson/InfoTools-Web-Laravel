<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Liste + recherche + pagination.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $products = Product::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->onEachSide(1)
            ->withQueryString();

        return view('products.index', compact('products', 'q'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Enregistrement d’un produit.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'between:0,999999.99'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['stock'] = $data['stock'] ?? 0;

        $product = Product::create($data);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Détail d’un produit.
     */
    public function show(Product $product)
    {
        // Récupérer les 5 derniers clients ayant acheté ce produit spécifique
        $recentBuyers = $product->invoiceLines() // Assure-toi d'avoir cette relation
            ->with('invoice.customer')
            ->latest()
            ->take(5)
            ->get();

        return view('products.show', compact('product', 'recentBuyers'));
    }

    /**
     * Formulaire d’édition.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Mise à jour d’un produit.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'between:0,999999.99'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['stock'] = $data['stock'] ?? 0;

        $product->update($data);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Produit mis à jour.');
    }

    /**
     * Suppression d’un produit.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produit supprimé.');
    }
}
