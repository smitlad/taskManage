@props(['page', 'title' => 'Tasks', 'auth' => 'required'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title }} · {{ config('app.name', 'Laravel') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body data-page="{{ $page }}" data-auth="{{ $auth }}" class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <nav class="mx-auto flex max-w-4xl items-center justify-between gap-4 px-4 py-3">
                <a href="{{ route('tasks.index') }}" class="text-lg font-semibold">{{ config('app.name', 'Laravel') }}</a>

                <div data-guest-only class="hidden items-center gap-4 text-sm">
                    <a href="{{ route('login') }}" class="hover:underline">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 font-medium text-white hover:bg-indigo-500">Register</a>
                </div>

                <div data-user-only class="hidden items-center gap-4 text-sm">
                    <span data-user-name class="text-gray-500 dark:text-gray-400"></span>
                    <a href="{{ route('tasks.create') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 font-medium text-white hover:bg-indigo-500">New task</a>
                    <button type="button" data-logout class="hover:underline">Log out</button>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-4xl px-4 py-8">
            <div data-flash class="mb-6 hidden rounded-md px-4 py-3 text-sm"></div>

            {{ $slot }}
        </main>
    </body>
</html>
