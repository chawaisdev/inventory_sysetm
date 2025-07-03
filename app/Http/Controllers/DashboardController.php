<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
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
        // $last7DaysSale = Sale::where('created_at', '>=', Carbon::now()->subDays(7))->sum('total_amount');
        // $thisMonthSale = Sale::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');

        $lowStockProducts = PurchaseItem::where('quantity', '<', 10)->get();

        return view('dashboard.index', compact(
            'totalSuppliers',
            'totalCustomers',
            'totalProducts',
            'totalPurchaseAmount',
            // 'last7DaysSale',
            // 'thisMonthSale',
            'lowStockProducts'
        ));
    }
}
