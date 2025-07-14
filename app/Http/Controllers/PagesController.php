<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
class PagesController extends Controller
{
    public function salerecord(Request $request)
    {
        $transactions = Transaction::with(['user', 'purchase'])->get();
        return view('report.index', compact('transactions'));
    }
}
