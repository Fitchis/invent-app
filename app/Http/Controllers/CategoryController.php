<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $cats = Category::withCount('products')->paginate(15);

        return view('categories.index', ['categories' => $cats]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ]);

        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Category created');
    }

    public function show(Category $category)
    {
        // not used for server-rendered UI
        return redirect()->route('categories.index');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'category_name' => 'sometimes|required|string|max:255|unique:categories,category_name,' . $category->id,
        ]);

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted');
    }
}
