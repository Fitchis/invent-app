<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::with('category')
            ->whereNotNull('product_stock')
            ->where('product_stock', '<', 5)
            ->orderBy('product_stock', 'asc')
            ->limit(20)
            ->get();

        return view('dashboard', compact('totalCategories', 'totalProducts', 'lowStockProducts'));
    }
}
