<x-layouts.app page="tasks.show" title="Task">
    <div data-task-id="{{ $task }}" class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <p data-loading class="text-sm text-gray-500 dark:text-gray-400">Loading task…</p>

        <article data-task class="hidden flex-col gap-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <h1 data-field="title" class="text-2xl font-semibold break-words"></h1>
                <span data-field="status" class="rounded-full px-2.5 py-1 text-xs font-medium"></span>
            </div>

            <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Due date</dt>
                    <dd data-field="due_date" class="mt-1 font-medium"></dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                    <dd data-field="created_at" class="mt-1 font-medium"></dd>
                </div>
                <div>
                    <dt class="text-gray-500 dark:text-gray-400">Last updated</dt>
                    <dd data-field="updated_at" class="mt-1 font-medium"></dd>
                </div>
            </dl>

            <div>
                <h2 class="text-sm text-gray-500 dark:text-gray-400">Description</h2>
                <p data-field="description" class="mt-1 whitespace-pre-line"></p>
            </div>

            <div class="flex items-center justify-between gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                <a href="{{ route('tasks.index') }}" class="text-sm hover:underline">← Back to tasks</a>

                <div class="flex items-center gap-3">
                    <button type="button" data-delete class="rounded-md px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 disabled:opacity-50 dark:text-red-400 dark:hover:bg-red-950">Delete</button>
                    <a href="{{ route('tasks.edit', $task) }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Edit</a>
                </div>
            </div>
        </article>
    </div>
</x-layouts.app>
