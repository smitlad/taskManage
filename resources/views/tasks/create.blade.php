<x-layouts.app page="tasks.create" title="New task">
    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="mb-6 text-2xl font-semibold">New task</h1>

        @include('tasks.partials.form', [
            'form' => 'task-create',
            'withStatus' => false,
            'submitLabel' => 'Create task',
            'cancelUrl' => route('tasks.index'),
        ])
    </div>
</x-layouts.app>
