<x-admin-layout :title="'Edit Product - MyPOS Admin'">

    <div class="max-w-lg rounded-xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <input type="text" name="category" value="{{ old('category', $product->category) }}"
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="rounded border-gray-300">
                Active
            </label>

            <div class="flex gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('admin.products.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
