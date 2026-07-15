<x-admin-layout :title="'Edit User - MyPOS Admin'">

    <div class="max-w-lg rounded-xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password"
                    class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected(old('role', $user->roles->first()?->name) == $role->name)>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
