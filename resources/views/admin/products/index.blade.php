<x-admin-layout :title="'Products - MyPOS Admin'">
    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('admin.products.create') }}"
            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            + Add Product
        </a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Variants</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $product->image_url ?? asset('images/product-placeholder.svg') }}"
                                alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover bg-gray-100">
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->category ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->variants_count }}</td>
                        <td class="px-4 py-3">
                            @if ($product->is_active)
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">Active</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>

                                <form method="POST" action="{{ route('admin.products.image', $product) }}"
                                    enctype="multipart/form-data" class="flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="file" name="image" accept="image/*" required class="w-32 text-xs">
                                    <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800">Upload</button>
                                </form>

                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
