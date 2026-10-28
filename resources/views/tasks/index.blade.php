<x-layouts.app page="tasks.index" title="My tasks">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">My tasks</h1>

        <select data-status-filter aria-label="Filter by status" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <option value="">All statuses</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In progress</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <p data-loading class="text-sm text-gray-500 dark:text-gray-400">Loading tasks…</p>

    <p data-empty class="hidden rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
        No tasks yet. <a href="{{ route('tasks.create') }}" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Create your first task</a>.
    </p>

    <ul data-task-list class="flex flex-col gap-3"></ul>

    <template data-task-template>
        <li>
            <a class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:border-indigo-400 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-500">
                <div class="min-w-0">
                    <p data-field="title" class="truncate font-medium"></p>
                    <p data-field="due_date" class="mt-1 text-sm text-gray-500 dark:text-gray-400"></p>
                </div>
                <span data-field="status" class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium"></span>
            </a>
        </li>
    </template>

    <nav data-pagination class="mt-6 hidden items-center justify-between text-sm">
        <button type="button" data-page-prev class="rounded-md border border-gray-300 px-3 py-1.5 disabled:opacity-40 dark:border-gray-700">Previous</button>
        <span data-page-info class="text-gray-500 dark:text-gray-400"></span>
        <button type="button" data-page-next class="rounded-md border border-gray-300 px-3 py-1.5 disabled:opacity-40 dark:border-gray-700">Next</button>
    </nav>
</x-layouts.app>
