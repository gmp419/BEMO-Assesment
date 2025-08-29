const BASE = '/api';

async function http(path, options = {}) {
    const res = await fetch(BASE + path, {
        headers: { 'Content-Type': 'application/json' },
        ...options,
    });
    if (!res.ok) throw new Error(await res.text());
    return res.json();
}

export const TicketsAPI = {
    list(params = {}) {
        const q = new URLSearchParams(params).toString();
        return http(`/tickets?${q}`);
    },
    create(payload) {
        return http('/tickets', { method: 'POST', body: JSON.stringify(payload) });
    },
    get(id) { return http(`/tickets/${id}`); },
    patch(id, payload) { return http(`/tickets/${id}`, { method: 'PATCH', body: JSON.stringify(payload) }); },
    classify(id) { return http(`/tickets/${id}/classify`, { method: 'POST' }); },
};

export const StatsAPI = { get() { return http('/stats'); } };