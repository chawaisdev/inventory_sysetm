<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
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
        $users = User::all(); // Suppliers
        $products = Product::all();
        $brands = Brand::all(); // Add this line

        return view('purchase.create', compact('users', 'products', 'brands'));
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
            'product_id' => 'required|array',
            'price' => 'required|array',
            'quantity' => 'required|array',
            'discount' => 'required|array',
            'line_total' => 'required|array',
        ]);

        // Create main purchase record
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

        foreach ($request->product_id as $index => $productId) {
            $price = $request->price[$index];
            $quantity = $request->quantity[$index];
            $discount = $request->discount[$index]; // ✅ Define this

            // Save purchase item
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'brand_id' => $request->brand_id[$index],
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
                'discount' => $discount,
                'line_total' => $request->line_total[$index],
            ]);

            // ✅ Update product purchase_price, discount, and stock
            $product = Product::find($productId);
            if ($product) {
                $product->purchase_price = $price;
                $product->discount = $discount; // ✅ Now works correctly
                $product->stock += $quantity;
                $product->save();
            }
        }

        // Create payment transaction
        Transaction::create([
            'user_id' => $request->user_id,
            'purchase_id' => $purchase->id,
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
    $products = Product::all(); // Ensure product has brand_id relation loaded
    $purchase = Purchase::findOrFail($id);
    $items = PurchaseItem::where('purchase_id', $id)->get();

    return view('purchase.edit', compact('purchase', 'users', 'brands', 'items', 'products'));
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

    public function storePayment(Request $request, Purchase $purchase)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'date' => 'required|date',
        ]);

        // Generate random transaction/invoice number
        $transactionNo = 'TXN-' . now()->format('Ymd') . '-' . rand(1000, 9999);

        // Save to transactions table
        Transaction::create([
            'user_id' => $purchase->user_id,
            'purchase_id' => $purchase->id,
            'amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'type' => 'credit',
            'date' => $request->date,
            'invoice_no' => $transactionNo, // ✅ add transaction number
            'note' => $request->note ?? 'Partial payment for purchase #' . $purchase->id,
        ]);

        // Update purchase payment amounts
        $purchase->paid_amount += $request->paid_amount;
        $purchase->due_amount = $purchase->total_amount - $purchase->paid_amount;
        $purchase->save();

        return redirect()->back()->with('success', 'Payment recorded successfully.');
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

        $purchase = Purchase::with('user')->findOrFail($request->purchase_id);
        $supplierId = $purchase->user_id;

        $item = PurchaseItem::where('purchase_id', $request->purchase_id)
            ->where('product_name', $request->product_name)
            ->firstOrFail();

        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Return quantity exceeds purchased quantity.');
        }

        $discount = $item->discount ?? 0;
        $unitNetPrice = $item->price - $discount;
        $returnAmount = $unitNetPrice * $request->quantity;

        PurchaseReturn::create([
            'purchase_id' => $request->purchase_id,
            'user_id' => $supplierId,
            'product_name' => $request->product_name,
            'price' => $item->price,
            'quantity' => $request->quantity,
            'return_amount' => $returnAmount,
            'return_date' => $request->return_date,
        ]);

        $item->quantity -= $request->quantity;
        $item->line_total = ($item->price - $discount) * $item->quantity;
        $item->save();

        $purchase->total_amount -= $returnAmount;
        $purchase->due_amount = $purchase->total_amount - $purchase->paid_amount;
        $purchase->save();

        $transactionNo = 'TXN-' . now()->format('Ymd') . '-' . rand(1000, 9999);

        Transaction::create([
            'user_id' => $supplierId,
            'purchase_id' => $purchase->id,
            'amount' => $returnAmount,
            'payment_method' => 'bank',
            'type' => 'credit',
            'date' => $request->return_date,
            'invoice_no' => $transactionNo,
            'note' => 'Returned: ' . $request->product_name,
        ]);

        return redirect()->back()->with('success', 'Product returned and records updated.');
    }

    public function getPurchaseReturns()
    {
        $returns = PurchaseReturn::with('purchase.user')->get();
        return view('purchase.return', compact('returns'));
    }
}
