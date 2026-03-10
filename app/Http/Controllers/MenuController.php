<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::where('seller_id', Auth::id());

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->latest()->get();

        return view('seller.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|integer|min:0',
            'category'    => 'required|in:makanan,minuman,snack',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url'   => 'nullable|url|max:2048',
        ]);

        $validated['seller_id']    = Auth::id();
        $validated['is_available'] = true;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        unset($validated['image_url']);
        Menu::create($validated);

        return back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, Menu $menu)
    {
        if ($menu->seller_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|integer|min:0',
            'category'    => 'required|in:makanan,minuman,snack',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url'   => 'nullable|url|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image && !str_starts_with($menu->image, 'http')) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        } elseif ($request->filled('image_url')) {
            if ($menu->image && !str_starts_with($menu->image, 'http')) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->image_url;
        } else {
            unset($validated['image']);
        }

        unset($validated['image_url']);
        $menu->update($validated);

        return back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function toggleAvailability(Menu $menu)
    {
        if ($menu->seller_id !== Auth::id()) {
            abort(403);
        }

        $menu->update(['is_available' => !$menu->is_available]);
        $status = $menu->is_available ? 'tersedia' : 'habis';

        return back()->with('success', "Status menu diubah menjadi {$status}!");
    }

    public function destroy(Menu $menu)
    {
        if ($menu->seller_id !== Auth::id()) {
            abort(403);
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus!');
    }
}
