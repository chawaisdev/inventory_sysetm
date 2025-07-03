<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\SaleItem; // Ensure you have this model
use App\Models\PurchaseItem;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSuppliers = User::where('user_type', 'supplier')->count();
        $totalCustomers = User::where('user_type', 'customer')->count();
        $totalProducts = Product::count();

        $totalPurchaseAmount = Purchase::sum('total_amount');
        $totalSalesAmount = Sale::sum('total_amount');

        $lowStockProducts = Product::where('stock', '<', 25)->paginate(2);

        // Assuming sale_items table has product_id
        $soldProducts = SaleItem::with('product')->paginate(5); // Show 5 per page

        return view('dashboard.index', compact(
            'totalSuppliers',
            'totalCustomers',
            'totalProducts',
            'totalPurchaseAmount',
            'totalSalesAmount',
            'lowStockProducts',
            'soldProducts'
        ));
    }
}
