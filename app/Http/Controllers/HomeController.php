<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->limit(12)->get();
        return view('welcome', compact('products'));
    }
}
