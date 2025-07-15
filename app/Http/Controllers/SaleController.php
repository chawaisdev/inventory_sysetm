<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('user')
        ->orderBy('date', 'asc') // oldest first
        ->get()
        ->unique('user_id')
        ->values();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('user_type', 'customer')->get();
        $brands = Brand::all();
        $products = Product::all();

        return view('sales.create', compact('users', 'brands', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id.*' => 'required|exists:products,id',
            'price.*' => 'required|numeric',
            'quantity.*' => 'required|integer|min:1',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'due_amount' => 'required|numeric',
            'payment_method' => 'required',
            'discount' => 'required|array',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'user_id' => $request->user_id,
                'invoice_no' => 'INV-' . strtoupper(uniqid()),
                'date' => $request->date,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->due_amount,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
            ]);

            foreach ($request->product_id as $index => $productId) {
                $quantity = $request->quantity[$index];
                $price = $request->price[$index];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $quantity,
                ]);

                $product = Product::find($productId);
                $product->stock -= $quantity;
                $product->save();
            }

            Transaction::create([
                'user_id' => $request->user_id,
                'purchase_id' => $sale->id, // can rename this to sale_id if needed
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'type' => 'Credit',
                'date' => $request->date,
                'note' => $request->note,
            ]);
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
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
    public function edit(string $id)
    {
        $sale = Sale::findOrFail($id);
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        // Find the sale record and update it
        $sale = Sale::findOrFail($id);
        $sale->product_id = $request->input('product_id');
        $sale->quantity = $request->input('quantity');
        $sale->sale_date = $request->input('sale_date');
        $sale->save();

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
