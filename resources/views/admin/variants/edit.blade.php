<x-admin-layout :title="'Edit Variant - MyPOS Admin'">

    <div class="max-w-lg rounded-xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.variants.update', $variant) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Product</label>
                <select name="product_id" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id', $variant->product_id) == $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Size</label>
                <select name="size" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    @foreach ($sizes as $size)
                        <option value="{{ $size->value }}" @selected(old('size', $variant->size) == $size->value)>{{ $size->label() }}</option>
                    @endforeach
                </select>
                @unless (in_array($variant->size, array_column($sizes, 'value')))
                    <p class="mt-1 text-xs text-amber-600">
                        Current value in database is "{{ $variant->size }}", which isn't one of the standard options. Saving will change it to your selection above.
                    </p>
                @endunless
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $variant->price) }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('admin.variants.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
