<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(15);
        $categories = Category::orderBy('category_name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_code' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'product_stock' => 'nullable|integer|min:0',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'location' => 'nullable|string|max:255',
            'category_date' => 'nullable|date',
        ]);

        // handle image upload
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('products', 'public');
            $data['product_image'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created');
    }

    public function show(Product $product)
    {
        // not used for server-rendered UI; redirect to manage
        return redirect()->route('products.index');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'product_code' => 'sometimes|required|string|max:255',
            'product_name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'product_stock' => 'nullable|integer|min:0',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location' => 'nullable|string|max:255',
            'category_date' => 'nullable|date',
        ]);

        // handle image upload
        if ($request->hasFile('product_image')) {
            // delete old image if exists
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }
            $path = $request->file('product_image')->store('products', 'public');
            $data['product_image'] = $path;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted');
    }
}
