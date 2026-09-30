import test from 'node:test';
import assert from 'node:assert/strict';
import { availableBookingTimes } from '../../resources/js/reservation-slots.js';

const date = '2026-10-01';
const now = Date.parse('2026-09-30T10:00:00+07:00');
const time = minutes => `${String(Math.floor(minutes / 60)).padStart(2, '0')}:${String(minutes % 60).padStart(2, '0')}`;
const schedule = (bookings = []) => Array.from({ length: 26 }, (_, index) => {
    const start = time(420 + index * 30);
    const end = time(450 + index * 30);
    return { start, end, is_available: !bookings.some(([from, to]) => from < end && to > start) };
});

test('approved 08:00 to 10:00 hides occupied starts but leaves 10:00 available', () => {
    const { starts, ends } = availableBookingTimes(schedule([['08:00', '10:00']]), date, '07:00', 6, now);
    assert.deepEqual(starts.filter(value => value >= '08:00' && value < '10:00'), []);
    assert.ok(starts.includes('10:00'));
    assert.deepEqual(ends, ['07:30', '08:00']);
});

test('end times stop at next booking and never jump across occupied slots', () => {
    const { ends } = availableBookingTimes(schedule([['08:00', '10:00'], ['12:00', '13:00']]), date, '10:00', 6, now);
    assert.deepEqual(ends, ['10:30', '11:00', '11:30', '12:00']);
});

test('six hour limit and closing time remain enforced', () => {
    assert.equal(availableBookingTimes(schedule(), date, '07:00', 6, now).ends.at(-1), '13:00');
    assert.deepEqual(availableBookingTimes(schedule(), date, '19:30', 6, now).ends, ['20:00']);
});

test('past starts in WIB disappear, including elapsed seconds', () => {
    const clock = Date.parse(`${date}T10:00:01+07:00`);
    assert.equal(availableBookingTimes(schedule(), date, '', 6, clock).starts[0], '10:30');
});

test('fully occupied day and stale selected start provide no usable end', () => {
    assert.deepEqual(availableBookingTimes(schedule([['07:00', '20:00']]), date, '08:00', 6, now), { starts: [], ends: [] });
    assert.deepEqual(availableBookingTimes(schedule([['08:00', '10:00']]), date, '08:30', 6, now).ends, []);
});

test('switching to a free facility or date restores previously occupied times', () => {
    assert.ok(!availableBookingTimes(schedule([['08:00', '10:00']]), date, '', 6, now).starts.includes('08:00'));
    assert.ok(availableBookingTimes(schedule(), date, '', 6, now).starts.includes('08:00'));
});
