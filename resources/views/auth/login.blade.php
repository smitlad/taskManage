<x-layouts.app page="login" title="Log in" auth="guest">
    <div class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="mb-6 text-2xl font-semibold">Log in</h1>

        <form data-form="login" class="flex flex-col gap-5" novalidate>
            <x-form-field name="email" label="Email" type="email" autocomplete="email" required autofocus />
            <x-form-field name="password" label="Password" type="password" autocomplete="current-password" required />

            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50">Log in</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Register</a>
        </p>
    </div>
</x-layouts.app>
