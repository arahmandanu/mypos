<x-admin-layout :title="'Users - MyPOS Admin'">
    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('admin.users.create') }}"
            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            + Add User
        </a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @foreach ($user->roles as $role)
                                <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs text-indigo-700">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">No users yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
