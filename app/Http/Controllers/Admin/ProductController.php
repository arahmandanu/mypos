<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->withCount('variants')->orderBy('name')->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            $product->delete();
        } catch (QueryException) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete this product — it has existing order history.');
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function updateImage(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $path = $request->file('image')->store('products', 'public');

        $product->update(['image_path' => $path]);

        return redirect()->route('admin.products.index')->with('success', "Image updated for {$product->name}.");
    }
}
