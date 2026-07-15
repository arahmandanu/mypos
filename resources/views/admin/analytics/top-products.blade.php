<x-admin-layout :title="'Top Product Sold - MyPOS Admin'">

    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm">
        <div>
            <label class="block text-sm font-medium text-gray-700">From</label>
            <input type="date" name="from" value="{{ $from->toDateString() }}"
                class="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">To</label>
            <input type="date" name="to" value="{{ $to->toDateString() }}"
                class="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Filter
        </button>
    </form>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Rank</th>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Quantity Sold</th>
                    <th class="px-4 py-3">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($topProducts as $index => $product)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->total_quantity }}</td>
                        <td class="px-4 py-3 text-gray-500">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">No sales in this range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
