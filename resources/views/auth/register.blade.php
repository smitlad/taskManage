<x-layouts.app page="register" title="Register" auth="guest">
    <div class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="mb-6 text-2xl font-semibold">Create an account</h1>

        <form data-form="register" class="flex flex-col gap-5" novalidate>
            <x-form-field name="name" label="Name" autocomplete="name" required autofocus />
            <x-form-field name="email" label="Email" type="email" autocomplete="email" required />
            <x-form-field name="password" label="Password" type="password" autocomplete="new-password" minlength="8" required />

            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50">Register</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Already registered?
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Log in</a>
        </p>
    </div>
</x-layouts.app>
