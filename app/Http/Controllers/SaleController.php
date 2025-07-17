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
        $sales = Sale::with(['user', 'saleItems.product']) // eager load product via saleItems
            ->orderBy('date', 'asc')
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
            'discount.*' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'due_amount' => 'required|numeric',
            'payment_method' => 'required',
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
                $discount = $request->discount[$index];

                $subTotal = $price * $quantity;
                $discountAmount = ($subTotal * $discount) / 100;
                $total = $subTotal - $discountAmount;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'discount' => $discount,
                    'total_price' => $total,
                ]);

                $product = Product::find($productId);
                $product->stock -= $quantity;
                $product->save();
            }

            Transaction::create([
                'user_id' => $request->user_id,
                'purchase_id' => $sale->id,
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
        $sale = Sale::with('items')->findOrFail($id);
        $users = User::where('user_type', 'customer')->get();
        $brands = Brand::all();
        $products = Product::all();

        return view('sales.edit', compact('sale', 'users', 'brands', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id.*' => 'required|exists:products,id',
            'price.*' => 'required|numeric',
            'quantity.*' => 'required|integer|min:1',
            'discount.*' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'due_amount' => 'required|numeric',
            'payment_method' => 'required',
            'date' => 'required|date',
        ]);

        // Update Sale
        $sale = Sale::findOrFail($id);
        $sale->update([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->due_amount,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
        ]);

        // Delete old SaleItems
        $sale->items()->delete();

        // Re-insert SaleItems
        foreach ($request->product_id as $index => $productId) {
            $price = $request->price[$index];
            $quantity = $request->quantity[$index];
            $discount = $request->discount[$index];

            $subTotal = $price * $quantity;
            $discountAmount = ($subTotal * $discount) / 100;
            $total = $subTotal - $discountAmount;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $price,
                'discount' => $discount,
                'total_price' => $total,
            ]);
        }

        // Update existing transaction or create one
        $transaction = Transaction::where('purchase_id', $sale->id)
            ->where('type', 'sale')
            ->first();

        if ($transaction) {
            $transaction->update([
                'user_id' => $request->user_id,
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'date' => $request->date,
                'note' => $request->note,
            ]);
        } else {
            Transaction::create([
                'user_id' => $request->user_id,
                'purchase_id' => $sale->id,
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'type' => 'credit', // or use 'credit' if preferred
                'date' => $request->date,
                'note' => $request->note,
            ]);
        }

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

    public function getSaleItems(Request $request)
    {
        $sale = SaleItem::with('product')->get();
        return view('sales.item', compact('sale'));
    }

    public function SalePayment(Request $request, Sale $sale)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'date' => 'required|date',
        ]);

        // Store in transactions
        Transaction::create([
        'user_id' => $sale->user_id,  // supplier
            'sale_id' => $sale->id,
            'amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'type' => 'credit',
            'date' => $request->date,
            'note' => $request->note,
        ]);

        // Update purchase paid_amount
        $sale->paid_amount += $request->paid_amount;
        $sale->due_amount = $sale->total_amount - $sale->paid_amount;
        $sale->save();

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

}
