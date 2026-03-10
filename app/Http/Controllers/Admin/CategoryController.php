<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        Category::create($validated);

        return back()->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return back()->with('success', "Kategori berhasil diperbarui.");
    }

    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();

        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}
