<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('brand')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $brands = Brand::all();
        return view('products.create', compact('products', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'brand_id' => 'nullable|string',
            'name' => 'required|string|max:255',
            'sale_price' => 'nullable|string',
        ]);

        $products = new Product();
        $products->brand_id = $request->brand_id;
        $products->name = $request->name;
        $products->sale_price = $request->sale_price;
        $products->save(); 

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        return view('products.edit', compact('product', 'brands'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'brand_id' => 'nullable|string',
            'name' => 'required|string|max:255',
            'sale_price' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $product->brand_id = $request->brand_id;
        $product->name = $request->name;
        $product->sale_price = $request->sale_price;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
