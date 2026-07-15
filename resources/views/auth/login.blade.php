<x-layout :title="'Login - MyPOS'">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-sm rounded-xl bg-white p-8 shadow">
            <h1 class="mb-6 text-center text-2xl font-semibold text-gray-800">MyPOS Login</h1>

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    Remember me
                </label>

                <button type="submit"
                    class="w-full rounded-md bg-indigo-600 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Sign in
                </button>
            </form>
        </div>
    </div>
</x-layout>
