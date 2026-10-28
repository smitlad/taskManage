const TOKEN_KEY = 'task-manager.token';
const USER_KEY = 'task-manager.user';

export class ApiError extends Error {
    constructor(status, payload) {
        super(payload?.message ?? `Request failed with status ${status}`);
        this.status = status;
        this.errors = payload?.errors ?? {};
    }
}

export const session = {
    token: () => localStorage.getItem(TOKEN_KEY),
    user: () => JSON.parse(localStorage.getItem(USER_KEY) ?? 'null'),
    start(token, user) {
        localStorage.setItem(TOKEN_KEY, token);
        localStorage.setItem(USER_KEY, JSON.stringify(user));
    },
    end() {
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
    },
};

/**
 * Call the JSON API, attaching the stored bearer token. On a 401 the stored
 * session is cleared and the user is sent back to the login page.
 */
export async function api(method, path, body = undefined) {
    const headers = { Accept: 'application/json' };

    if (body !== undefined) {
        headers['Content-Type'] = 'application/json';
    }

    if (session.token()) {
        headers.Authorization = `Bearer ${session.token()}`;
    }

    const response = await fetch(`/api${path}`, {
        method,
        headers,
        body: body === undefined ? undefined : JSON.stringify(body),
    });

    const payload = await response.json().catch(() => null);

    if (response.status === 401 && session.token()) {
        session.end();
        window.location.href = '/login';
    }

    if (!response.ok) {
        throw new ApiError(response.status, payload);
    }

    return payload;
}
