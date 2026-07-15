<x-admin-layout :title="'Add Topping - MyPOS Admin'">

    <div class="max-w-lg rounded-xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.toppings.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
                Active
            </label>

            <div class="flex gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('admin.toppings.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
