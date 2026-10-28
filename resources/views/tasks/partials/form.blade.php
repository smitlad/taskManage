<form data-form="{{ $form }}" class="flex flex-col gap-5" novalidate>
    <x-form-field name="title" label="Title" maxlength="255" required />
    <x-form-field name="description" label="Description" type="textarea" />
    <x-form-field name="due_date" label="Due date" type="date" />

    @if ($withStatus)
        <x-form-field name="status" label="Status" type="select">
            <option value="pending">Pending</option>
            <option value="in_progress">In progress</option>
            <option value="completed">Completed</option>
        </x-form-field>
    @endif

    <div class="flex items-center justify-end gap-3">
        <a href="{{ $cancelUrl }}" class="rounded-md px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-800">Cancel</a>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-50">{{ $submitLabel }}</button>
    </div>
</form>
