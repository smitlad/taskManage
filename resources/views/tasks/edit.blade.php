<x-layouts.app page="tasks.edit" title="Edit task">
    <div data-task-id="{{ $task }}" class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="mb-6 text-2xl font-semibold">Edit task</h1>

        @include('tasks.partials.form', [
            'form' => 'task-edit',
            'withStatus' => true,
            'submitLabel' => 'Save changes',
            'cancelUrl' => route('tasks.show', $task),
        ])
    </div>
</x-layouts.app>
