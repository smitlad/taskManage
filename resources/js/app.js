import { api, ApiError, session } from './api';

const FLASH_KEY = 'task-manager.flash';

const STATUS_LABELS = {
    pending: 'Pending',
    in_progress: 'In progress',
    completed: 'Completed',
};

const STATUS_CLASSES = {
    pending: 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
    in_progress: 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
    completed: 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
};

const $ = (selector, root = document) => root.querySelector(selector);

function setVisible(element, visible, display = 'block') {
    element.classList.toggle('hidden', !visible);
    element.classList.toggle(display, visible);
}

function redirectWithFlash(url, message) {
    sessionStorage.setItem(FLASH_KEY, message);
    window.location.href = url;
}

function showFlash(message, type = 'success') {
    const flash = $('[data-flash]');
    flash.textContent = message;
    flash.className = `mb-6 rounded-md px-4 py-3 text-sm ${
        type === 'error'
            ? 'bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300'
            : 'bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300'
    }`;
}

function formatDate(value) {
    if (!value) {
        return '—';
    }

    // Plain Y-m-d dates are parsed as local midnight so they don't shift a day in negative UTC offsets.
    const date = /^\d{4}-\d{2}-\d{2}$/.test(value) ? new Date(`${value}T00:00:00`) : new Date(value);

    return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}

function renderStatus(element, status) {
    element.textContent = STATUS_LABELS[status] ?? status;
    element.classList.add(...STATUS_CLASSES[status].split(' '));
}

function formData(form) {
    return Object.fromEntries(new FormData(form).entries());
}

function clearErrors(form) {
    form.querySelectorAll('[data-error]').forEach((element) => {
        element.textContent = '';
        element.classList.add('hidden');
    });
}

function showErrors(form, error) {
    if (!(error instanceof ApiError)) {
        showFlash('Something went wrong. Please try again.', 'error');

        return;
    }

    const fieldErrors = Object.entries(error.errors);

    if (fieldErrors.length === 0) {
        showFlash(error.message, 'error');

        return;
    }

    fieldErrors.forEach(([field, messages]) => {
        const element = form.querySelector(`[data-error="${field}"]`);

        if (element) {
            element.textContent = messages[0];
            element.classList.remove('hidden');
        }
    });
}

/**
 * Wire a form to an async submit handler: disables the submit button while
 * the request runs and renders validation errors next to their fields.
 */
function handleSubmit(form, onSubmit) {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const button = form.querySelector('[type="submit"]');
        button.disabled = true;
        clearErrors(form);

        try {
            await onSubmit(formData(form));
        } catch (error) {
            showErrors(form, error);
            button.disabled = false;
        }
    });
}

/**
 * Turn empty optional inputs into nulls so the API clears those columns.
 */
function taskPayload(data) {
    return {
        ...data,
        description: data.description || null,
        due_date: data.due_date || null,
    };
}

const pages = {
    login() {
        handleSubmit($('[data-form="login"]'), async (data) => {
            const { token, user } = await api('POST', '/login', data);
            session.start(token, user);
            redirectWithFlash('/tasks', `Welcome back, ${user.name}!`);
        });
    },

    register() {
        handleSubmit($('[data-form="register"]'), async (data) => {
            const { token, user } = await api('POST', '/register', data);
            session.start(token, user);
            redirectWithFlash('/tasks', 'Your account has been created.');
        });
    },

    'tasks.index'() {
        const list = $('[data-task-list]');
        const template = $('[data-task-template]');
        const filter = $('[data-status-filter]');
        const pagination = $('[data-pagination]');
        let currentPage = 1;
        let lastPage = 1;

        async function load(page) {
            setVisible($('[data-loading]'), true);

            const params = new URLSearchParams({ page });

            if (filter.value) {
                params.set('status', filter.value);
            }

            const result = await api('GET', `/tasks?${params}`);
            currentPage = result.current_page;
            lastPage = result.last_page;

            list.replaceChildren(
                ...result.data.map((task) => {
                    const item = template.content.cloneNode(true);
                    $('a', item).href = `/tasks/${task.id}`;
                    $('[data-field="title"]', item).textContent = task.title;
                    $('[data-field="due_date"]', item).textContent = `Due ${formatDate(task.due_date)}`;
                    renderStatus($('[data-field="status"]', item), task.status);

                    return item;
                }),
            );

            setVisible($('[data-loading]'), false);
            setVisible($('[data-empty]'), result.total === 0);
            setVisible(pagination, lastPage > 1, 'flex');
            $('[data-page-info]').textContent = `Page ${currentPage} of ${lastPage}`;
            $('[data-page-prev]').disabled = currentPage <= 1;
            $('[data-page-next]').disabled = currentPage >= lastPage;
        }

        filter.addEventListener('change', () => load(1));
        $('[data-page-prev]').addEventListener('click', () => load(currentPage - 1));
        $('[data-page-next]').addEventListener('click', () => load(currentPage + 1));

        load(1);
    },

    'tasks.create'() {
        handleSubmit($('[data-form="task-create"]'), async (data) => {
            const { task } = await api('POST', '/tasks', taskPayload(data));
            redirectWithFlash(`/tasks/${task.id}`, 'Task created.');
        });
    },

    async 'tasks.edit'() {
        const taskId = $('[data-task-id]').dataset.taskId;
        const form = $('[data-form="task-edit"]');
        const { task } = await api('GET', `/tasks/${taskId}`);

        form.elements.title.value = task.title;
        form.elements.description.value = task.description ?? '';
        form.elements.due_date.value = task.due_date ?? '';
        form.elements.status.value = task.status;

        handleSubmit(form, async (data) => {
            await api('PUT', `/tasks/${taskId}`, taskPayload(data));
            redirectWithFlash(`/tasks/${taskId}`, 'Task updated.');
        });
    },

    async 'tasks.show'() {
        const taskId = $('[data-task-id]').dataset.taskId;
        const { task } = await api('GET', `/tasks/${taskId}`);
        const article = $('[data-task]');

        $('[data-field="title"]', article).textContent = task.title;
        $('[data-field="description"]', article).textContent = task.description || 'No description.';
        $('[data-field="due_date"]', article).textContent = formatDate(task.due_date);
        $('[data-field="created_at"]', article).textContent = formatDate(task.created_at);
        $('[data-field="updated_at"]', article).textContent = formatDate(task.updated_at);
        renderStatus($('[data-field="status"]', article), task.status);

        setVisible($('[data-loading]'), false);
        setVisible(article, true, 'flex');

        $('[data-delete]').addEventListener('click', async (event) => {
            if (!confirm('Delete this task? This cannot be undone.')) {
                return;
            }

            const button = event.currentTarget;
            button.disabled = true;

            try {
                await api('DELETE', `/tasks/${taskId}`);
                redirectWithFlash('/tasks', 'Task deleted.');
            } catch (error) {
                showFlash(error.message, 'error');
                button.disabled = false;
            }
        });
    },
};

function initNavigation() {
    const user = session.user();

    setVisible($('[data-guest-only]'), !user, 'flex');
    setVisible($('[data-user-only]'), Boolean(user), 'flex');

    if (user) {
        $('[data-user-name]').textContent = user.name;
    }

    $('[data-logout]').addEventListener('click', async () => {
        await api('POST', '/logout').catch(() => {});
        session.end();
        redirectWithFlash('/login', 'You have been logged out.');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const { page, auth } = document.body.dataset;

    if (auth === 'required' && !session.token()) {
        window.location.href = '/login';

        return;
    }

    if (auth === 'guest' && session.token()) {
        window.location.href = '/tasks';

        return;
    }

    initNavigation();

    const flash = sessionStorage.getItem(FLASH_KEY);

    if (flash) {
        sessionStorage.removeItem(FLASH_KEY);
        showFlash(flash);
    }

    Promise.resolve(pages[page]?.()).catch((error) => {
        $('[data-loading]')?.classList.add('hidden');
        showFlash(error.status === 404 ? 'That task could not be found.' : error.message, 'error');
    });
});
