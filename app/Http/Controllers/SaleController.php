<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
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
        $sale = Sale::with(['saleItems.product', 'user'])->findOrFail($id);
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

        $sale->saleItems()->delete();

    foreach ($request->product_id as $index => $productId) {
        $price = floatval($request->price[$index]);
        $quantity = intval($request->quantity[$index]);
        $discount = floatval($request->discount[$index]);

        $subTotal = $price * $quantity;
        $discountAmount = ($subTotal * $discount) / 100;
        $total = $subTotal - $discountAmount;

        // Round total to 2 decimals to avoid float precision issues
        $total = round($total, 2);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => round($price, 2),
            'discount' => round($discount, 2),
            'total_price' => $total,
        ]);
    }

        $transaction = Transaction::where('sale_id', $sale->id)->first();

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
                'sale_id' => $sale->id,
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'type' => 'credit',
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


    public function storeReturn(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'return_date' => 'required|date',
        ]);

        $sale = Sale::with('user')->findOrFail($request->sale_id);
        $customerId = $sale->user_id;

        $item = SaleItem::where('sale_id', $request->sale_id)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Return quantity exceeds sold quantity.');
        }

        $unitPrice = $item->unit_price;
        $returnAmount = $unitPrice * $request->quantity;

        // Save Sale Return
        SaleReturn::create([
            'sale_id' => $request->sale_id,
            'user_id' => $customerId,
            'product_id' => $request->product_id,
            'price' => $unitPrice,
            'quantity' => $request->quantity,
            'return_amount' => $returnAmount,
            'return_date' => $request->return_date,
        ]);

        // Update SaleItem
        $item->quantity -= $request->quantity;
        $item->total_price = $item->quantity * $unitPrice;
        $item->save();

        // Update Sale
        $sale->total_amount -= $returnAmount;
        $sale->due_amount = $sale->total_amount - $sale->paid_amount;
        $sale->save();

        // Log Transaction with custom fields
        Transaction::create([
            'invoice_id' => $sale->invoice_no,
            'user_id' => $customerId,
            'sale_id' => $sale->id,
            'amount' => $returnAmount,
            'payment_method' => 'bank',
            'type' => 'credit',
            'date' => $request->return_date,
            'note' => 'Return Sale',
        ]);

        return redirect()->back()->with('success', 'Product returned successfully.');
    }



}
