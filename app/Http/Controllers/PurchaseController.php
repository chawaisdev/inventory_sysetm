<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Brand;
use App\Models\Transaction;
use App\Models\PurchaseItem;
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with('user')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('purchases')
                    ->groupBy('user_id');
            })
            ->get();
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('user_type', 'supplier')->get();
        $brands = Brand::all();
        return view('purchase.create', compact('users', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'brand_id' => 'required|array',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
            'product_name' => 'required|array',
            'price' => 'required|array',
            'quantity' => 'required|array',
            'discount' => 'required|array',
            'line_total' => 'required|array',
        ]);

        $purchase = Purchase::create([
            'user_id' => $request->user_id,
            'invoice_no' => 'INV-' . date('Y') . '-' . rand(1000, 9999),
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->total_amount - $request->paid_amount,
            'payment_method' => $request->payment_method,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        foreach ($request->product_name as $index => $name) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'brand_id' => $request->brand_id[$index],
                'product_name' => $name,
                'price' => $request->price[$index],
                'quantity' => $request->quantity[$index],
                'discount' => $request->discount[$index],
                'line_total' => $request->line_total[$index],
            ]);
        }

        // ✅ Include purchase_id in the transaction
        Transaction::create([
            'user_id' => $request->user_id,
            'purchase_id' => $purchase->id, // important line
            'amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'type' => 'credit',
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return redirect()->route('purchase.index')->with('success', 'Purchase created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        // Get all purchases for this supplier user_id
        $purchases = Purchase::with('user')
            ->where('user_id', $user_id)
            ->whereHas('user', function ($query) {
                $query->where('user_type', 'supplier');
            })
            ->get();

        $totalSales = $purchases->sum('total_amount');
        $paidAmount = $purchases->sum('paid_amount');
        $dueAmount = $purchases->sum('due_amount');
        $totalOrders = $purchases->count();

        return view('purchase.show', compact(
            'purchases', 'totalSales', 'paidAmount', 'dueAmount', 'totalOrders'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::all(); 
        $brands = Brand::all(); 
        $purchase = Purchase::findOrFail($id);
        $items = PurchaseItem::where('purchase_id', $id)->get();

        return view('purchase.edit', compact('purchase', 'users', 'brands', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'brand_id' => 'required|array',
            'brand_id.*' => 'required|exists:brands,id',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
            'product_name' => 'required|array',
            'price' => 'required|array',
            'quantity' => 'required|array',
            'discount' => 'required|array',
            'line_total' => 'required|array',
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->update([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->total_amount - $request->paid_amount,
            'payment_method' => $request->payment_method,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        // Remove old items
        PurchaseItem::where('purchase_id', $purchase->id)->delete();

        // Add updated items
        foreach ($request->product_name as $index => $name) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'brand_id' => $request->brand_id[$index],
                'product_name' => $name,
                'price' => $request->price[$index],
                'quantity' => $request->quantity[$index],
                'discount' => $request->discount[$index],
                'line_total' => $request->line_total[$index],
            ]);
        }

        $transaction = Transaction::where('purchase_id', $purchase->id)->first();

        if ($transaction) {
            $transaction->update([
                'user_id' => $request->user_id,
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'type' => 'credit',
                'date' => $request->date,
                'note' => $request->note,
            ]);
        } else {
            Transaction::create([
                'user_id' => $request->user_id,
                'purchase_id' => $purchase->id,
                'amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'type' => 'credit',
                'date' => $request->date,
                'note' => $request->note,
            ]);
        }
        return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('purchase.index')->with('success', 'Purchase deleted successfully.');
    }

    public function getPurchaseItems()
    {
        $items = PurchaseItem::with('purchase.user')->get();

        return view('purchase.items', compact('items'));
    }

    public function return(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'return_date' => 'required|date',
        ]);

        $returnAmount = $request->price * $request->quantity;

        PurchaseReturn::create([
            'purchase_id' => $request->purchase_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'return_amount' => $returnAmount,
            'return_date' => $request->return_date,
        ]);

        // Optional: update Purchase total_amount
        $purchase = Purchase::findOrFail($request->purchase_id);
        $purchase->total_amount -= $returnAmount;
        $purchase->due_amount = $purchase->total_amount - $purchase->paid_amount;
        $purchase->save();

        return view('purchase.return');

    }

    public function storePayment(Request $request, Purchase $purchase)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'date' => 'required|date',
        ]);

        // Store in transactions
        Transaction::create([
        'user_id' => $purchase->user_id,  // supplier
            'purchase_id' => $purchase->id,
            'amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'type' => 'credit',
            'date' => $request->date,
            'note' => $request->note,
        ]);

        // Update purchase paid_amount
        $purchase->paid_amount += $request->paid_amount;
        $purchase->due_amount = $purchase->total_amount - $purchase->paid_amount;
        $purchase->save();

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

}
