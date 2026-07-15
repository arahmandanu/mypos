<x-admin-layout :title="'Toppings - MyPOS Admin'">
    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('admin.toppings.create') }}"
            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            + Add Topping
        </a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($toppings as $topping)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $topping->name }}</td>
                        <td class="px-4 py-3 text-gray-500">Rp {{ number_format($topping->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if ($topping->is_active)
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">Active</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.toppings.edit', $topping) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                <form method="POST" action="{{ route('admin.toppings.destroy', $topping) }}"
                                    onsubmit="return confirm('Delete this topping?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">No toppings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
