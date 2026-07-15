<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductSize;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(): View
    {
        $variants = ProductVariant::with('product')->orderBy('product_id')->orderBy('size')->get();

        return view('admin.variants.index', compact('variants'));
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get();
        $sizes = ProductSize::cases();

        return view('admin.variants.create', compact('products', 'sizes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'size' => ['required', new Enum(ProductSize::class)],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        ProductVariant::create($validated);

        return redirect()->route('admin.variants.index')->with('success', 'Variant created.');
    }

    public function edit(ProductVariant $variant): View
    {
        $products = Product::orderBy('name')->get();
        $sizes = ProductSize::cases();

        return view('admin.variants.edit', compact('variant', 'products', 'sizes'));
    }

    public function update(Request $request, ProductVariant $variant): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'size' => ['required', new Enum(ProductSize::class)],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $variant->update($validated);

        return redirect()->route('admin.variants.index')->with('success', 'Variant updated.');
    }

    public function destroy(ProductVariant $variant): RedirectResponse
    {
        try {
            $variant->delete();
        } catch (QueryException) {
            return redirect()->route('admin.variants.index')
                ->with('error', 'Cannot delete this variant — it has existing order history.');
        }

        return redirect()->route('admin.variants.index')->with('success', 'Variant deleted.');
    }
}
