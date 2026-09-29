import assert from 'node:assert/strict';
import { test } from 'node:test';
import { availableEndTimes, initReservationSchedule } from '../../resources/js/reservation-schedule.js';

globalThis.window = { location: { href: 'https://example.test/reservation' } };
globalThis.Option = class {
    constructor(text, value) { this.text = text; this.value = value; }
};

function schedule(bookings = []) {
    const time = (minutes) => `${String(Math.floor(minutes / 60)).padStart(2, '0')}:${String(minutes % 60).padStart(2, '0')}`;
    return Array.from({ length: 26 }, (_, index) => {
        const start = time(420 + index * 30);
        const end = time(450 + index * 30);
        return { start, end, is_available: !bookings.some(([from, to]) => from < end && to > start) };
    });
}

function formFixture() {
    const control = (value = '') => ({
        value, dataset: {}, validity: { valid: true }, handlers: {},
        addEventListener(event, handler) { this.handlers[event] = handler; },
        replaceChildren(...options) { this.options = options; },
    });
    const fields = {
        facility_id: control('1'), tanggal: control('2026-10-01'),
        start_time: control(), end_time: control(),
    };
    fields.facility_id.selectedOptions = [{ dataset: { scheduleUrl: '/facilities/1/schedule' } }];
    const submit = control();
    const status = control();
    const retry = control();
    const form = {
        ...control(),
        elements: { namedItem: (name) => fields[name] },
        querySelector: (selector) => ({ '[type="submit"]': submit, '[data-schedule-status]': status, '[data-schedule-retry]': retry })[selector],
    };
    return { form, fields, submit, status, retry };
}

test('end times stop at the next booking, but allow back-to-back reservations', () => {
    const slots = schedule([['09:00', '12:00'], ['15:00', '16:00']]);
    assert.deepEqual(availableEndTimes(slots, '08:00'), ['08:30', '09:00']);
    assert.deepEqual(availableEndTimes(slots, '09:00'), []);
    assert.equal(availableEndTimes(slots, '12:00').at(-1), '15:00');
    assert.deepEqual(availableEndTimes(slots, '19:30'), ['20:00']);
});

test('dropdown removes 07:00–12:00 booking and restores only valid old selections', async (t) => {
    t.mock.method(globalThis, 'fetch', async () => ({ ok: true, json: async () => ({ slots: schedule([['07:00', '12:00']]) }) }));
    const { form, fields, submit } = formFixture();
    fields.start_time.dataset.selected = '08:00';
    fields.end_time.dataset.selected = '13:00';
    await initReservationSchedule(form);
    assert.equal(fields.start_time.options[1].value, '12:00');
    assert.equal(fields.start_time.value, '');
    assert.equal(submit.disabled, true);
    fields.start_time.value = '12:00';
    fields.start_time.handlers.change();
    assert.equal(fields.end_time.options[1].value, '12:30');
    fields.end_time.value = '13:00';
    fields.end_time.handlers.change();
    assert.equal(submit.disabled, false);
});

test('changing date ignores stale responses and handles fully booked days and retry', async (t) => {
    const requests = [];
    t.mock.method(globalThis, 'fetch', (url) => new Promise((resolve, reject) => requests.push({ url, resolve, reject })));
    const { form, fields, submit, retry } = formFixture();
    const first = initReservationSchedule(form);
    fields.tanggal.value = '2026-10-02';
    const second = fields.tanggal.handlers.change();
    assert.equal(requests[1].url.searchParams.get('date'), '2026-10-02');
    requests[1].resolve({ ok: true, json: async () => ({ slots: schedule([['07:00', '20:00']]) }) });
    await second;
    requests[0].resolve({ ok: true, json: async () => ({ slots: schedule() }) });
    await first;
    assert.equal(fields.start_time.options.length, 1);
    assert.equal(submit.disabled, true);
    const failed = fields.tanggal.handlers.change();
    requests[2].reject(new Error('Network failure'));
    await failed;
    assert.equal(retry.hidden, false);
    assert.equal(submit.disabled, true);
    const retried = retry.handlers.click();
    requests[3].resolve({ ok: true, json: async () => ({ slots: schedule() }) });
    await retried;
    assert.equal(fields.start_time.options.length, 27);
    assert.equal(retry.hidden, true);
});
