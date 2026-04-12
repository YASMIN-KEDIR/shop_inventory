<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'nullable', // Added Category
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        Product::create($request->all());
        return redirect('/products')->with('success', 'New product added!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect('/products')->with('success', 'Product updated!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect('/products')->with('success', 'Product deleted!');
    }

    public function sell(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $amount = $request->input('amount');

        if ($product->stock >= $amount && $amount > 0) {
            $product->decrement('stock', $amount);
            $product->increment('total_sold', $amount);
            
            $collected = $product->selling_price * $amount;
            return redirect('/products')->with('success', "Sold $amount units. Collected " . number_format($collected, 2) . " ETB.");
        }

        return redirect('/products')->with('error', 'Check stock or amount!');
    }
}