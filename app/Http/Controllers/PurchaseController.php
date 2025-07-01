<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\User;
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
        return view('purchase.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
            'product_name' => 'required|array',
            'price' => 'required|array',
            'quantity' => 'required|array',
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
                'product_name' => $name,
                'price' => $request->price[$index],
                'quantity' => $request->quantity[$index],
                'line_total' => $request->line_total[$index],
            ]);
        }

        return redirect()->route('purchase.index')->with('success', 'Purchase created successfully.');
    }




    /**
     * Display the specified resource.
     */
public function show()
{
    $purchases = Purchase::with('user')
        ->whereHas('user', function ($query) {
            $query->where('user_type', 'supplier');
        })
        ->get();

    $totalSales = $purchases->sum('total_amount');
    $paidAmount = $purchases->sum('paid_amount');
    $dueAmount = $purchases->sum('due_amount');
    $totalOrders = $purchases->count();

    $supplierNames = $purchases->pluck('user.name')->unique();
    $supplierName = $supplierNames->count() === 1 ? $supplierNames->first() ?? 'N/A' : 'Multiple Suppliers';

    return view('purchase.show', compact('purchases', 'totalSales', 'paidAmount', 'dueAmount', 'totalOrders', 'supplierName'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::all(); 
        $purchase = Purchase::findOrFail($id);
        return view('purchase.edit', compact('purchase', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
            'product_name' => 'required|array',
            'price' => 'required|array',
            'quantity' => 'required|array',
            'line_total' => 'required|array',
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->user_id = $request->user_id;
        $purchase->total_amount = $request->total_amount;
        $purchase->paid_amount = $request->paid_amount;
        $purchase->due_amount = $request->total_amount - $request->paid_amount;
        $purchase->payment_method = $request->payment_method;
        $purchase->date = $request->date;
        $purchase->note = $request->note;
        $purchase->save();

        // Delete existing purchase items for fresh update
        $purchase->items()->delete();

        // Insert updated purchase items
        foreach ($request->product_name as $index => $name) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_name' => $name,
                'price' => $request->price[$index],
                'quantity' => $request->quantity[$index],
                'line_total' => $request->line_total[$index],
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
}
