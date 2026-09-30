export function availableBookingTimes(slots, date, start, maxHours, now = Date.now()) {
    const starts = slots.filter(slot => slot.is_available
        && new Date(`${date}T${slot.start}:00+07:00`).getTime() >= now)
        .map(slot => slot.start);
    const ends = [];
    if (!starts.includes(start)) return { starts, ends };

    const startIndex = slots.findIndex(slot => slot.start === start);
    const minutes = time => Number(time.slice(0, 2)) * 60 + Number(time.slice(3, 5));
    let next = start;
    for (const slot of slots.slice(startIndex)) {
        if (!slot.is_available || slot.start !== next || minutes(slot.end) - minutes(start) > maxHours * 60) break;
        ends.push(slot.end);
        next = slot.end;
    }
    return { starts, ends };
}

export function initializeReservationSlots(form) {
    if (!form) return;
    const facility = form.elements.facility_id;
    const date = form.elements.tanggal;
    const start = form.elements.start_time;
    const end = form.elements.end_time;
    const submit = form.querySelector('[type="submit"]');
    const status = form.querySelector('[data-schedule-status]');
    const retry = form.querySelector('[data-schedule-retry]');
    const maxHours = Number(form.dataset.maxDurationHours);
    const serverNow = Date.parse(form.dataset.serverNow);
    const initializedAt = performance.now();
    const currentTime = () => serverNow + performance.now() - initializedAt;
    let slots = [];
    let controller;
    let version = 0;
    let ready = false;

    function options(select, values, placeholder, selected = select.value) {
        select.replaceChildren(new Option(placeholder, ''), ...values.map(value => new Option(`${value} WIB`, value)));
        select.value = values.includes(selected) ? selected : '';
        select.disabled = values.length === 0;
    }

    function updateEnd(selected = end.value) {
        const { ends } = availableBookingTimes(slots, date.value, start.value, maxHours, currentTime());
        options(end, ends, '-- Jam Selesai --', selected);
        submit.disabled = !ready || !start.value || !end.value;
    }

    async function refresh(preferredStart = start.value, preferredEnd = end.value) {
        const requestVersion = ++version;
        controller?.abort();
        ready = false;
        slots = [];
        options(start, [], '-- Jam Mulai --');
        options(end, [], '-- Jam Selesai --');
        submit.disabled = true;
        retry.hidden = true;
        const urlValue = facility.selectedOptions[0]?.dataset.scheduleUrl;
        if (!urlValue || !date.value || !date.checkValidity()) {
            status.textContent = 'Pilih fasilitas dan tanggal yang valid untuk melihat jam tersedia.';
            return;
        }
        status.textContent = 'Memuat jam tersedia...';
        controller = new AbortController();
        try {
            const url = new URL(urlValue, window.location.href);
            url.searchParams.set('date', date.value);
            const response = await fetch(url, { signal: controller.signal, cache: 'no-store', headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Schedule request failed');
            const data = await response.json();
            if (requestVersion !== version) return;
            if (data.date !== date.value || String(data.facility?.id) !== facility.value || !Array.isArray(data.slots)) {
                throw new Error('Invalid schedule response');
            }
            slots = data.slots;
            ready = true;
            const { starts } = availableBookingTimes(slots, date.value, '', maxHours, currentTime());
            options(start, starts, '-- Jam Mulai --', preferredStart);
            updateEnd(preferredEnd);
            status.textContent = starts.length
                ? 'Hanya jam yang tersedia ditampilkan. Jadwal yang sudah disetujui tidak dapat dipilih.'
                : 'Tidak ada jam tersedia untuk fasilitas dan tanggal ini. Pilih tanggal atau fasilitas lain.';
            if (preferredStart && !starts.includes(preferredStart)) {
                status.textContent += ' Jam mulai sebelumnya sudah tidak tersedia; silakan pilih ulang.';
            }
        } catch (error) {
            if (requestVersion !== version || error.name === 'AbortError') return;
            status.textContent = 'Jadwal gagal dimuat. Coba lagi sebelum mengajukan reservasi.';
            retry.hidden = false;
        }
    }

    facility.addEventListener('change', () => refresh());
    date.addEventListener('change', () => refresh());
    start.addEventListener('change', () => updateEnd());
    end.addEventListener('change', () => { submit.disabled = !ready || !start.value || !end.value; });
    retry.addEventListener('click', () => refresh());
    form.addEventListener('submit', event => {
        if (!ready || !start.value || !end.value) event.preventDefault();
    });
    window.addEventListener('focus', () => refresh());
    window.setInterval(() => {
        if (!document.hidden && !form.closest('#reservationFormWrapper')?.classList.contains('hidden')) refresh();
    }, 60000);
    refresh(start.dataset.selected, end.dataset.selected);
}
