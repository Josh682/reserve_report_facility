export function availableEndTimes(slots, startTime) {
    const startIndex = slots.findIndex((slot) => slot.start === startTime);
    const times = [];

    if (startIndex < 0) return times;

    let nextStart = startTime;
    for (const slot of slots.slice(startIndex)) {
        if (!slot.is_available || slot.start !== nextStart) break;
        times.push(slot.end);
        nextStart = slot.end;
    }

    return times;
}

export function initReservationSchedule(form) {
    const facility = form.elements.namedItem('facility_id');
    const date = form.elements.namedItem('tanggal');
    const start = form.elements.namedItem('start_time');
    const end = form.elements.namedItem('end_time');
    const submit = form.querySelector('[type="submit"]');
    const status = form.querySelector('[data-schedule-status]');
    const retry = form.querySelector('[data-schedule-retry]');
    let slots = [];
    let requestId = 0;
    let ready = false;

    function setOptions(select, times, placeholder, selected = '') {
        select.replaceChildren(new Option(placeholder, ''), ...times.map((time) => new Option(`${time} WIB`, time)));
        select.value = times.includes(selected) ? selected : '';
        select.disabled = times.length === 0;
    }

    function updateEnd(selected = end.value) {
        setOptions(end, availableEndTimes(slots, start.value), '-- Jam Selesai --', selected);
        submit.disabled = !ready || !start.value || !end.value;
    }

    async function loadSchedule(selectedStart = '', selectedEnd = '') {
        const currentRequest = ++requestId;
        ready = false;
        slots = [];
        setOptions(start, [], '-- Jam Mulai --');
        setOptions(end, [], '-- Jam Selesai --');
        submit.disabled = true;
        retry.hidden = true;

        const scheduleUrl = facility.selectedOptions[0]?.dataset.scheduleUrl;
        if (!scheduleUrl || !date.value || !date.validity.valid) {
            status.textContent = 'Pilih ruangan dan tanggal yang valid untuk melihat jam yang tersedia.';
            return;
        }

        status.textContent = 'Memuat jam yang tersedia...';

        try {
            const url = new URL(scheduleUrl, window.location.href);
            url.searchParams.set('date', date.value);
            const response = await fetch(url, { headers: { Accept: 'application/json' }, cache: 'no-store' });
            if (!response.ok) throw new Error('Jadwal tidak tersedia');
            const data = await response.json();
            if (currentRequest !== requestId) return;
            if (!Array.isArray(data.slots)) throw new Error('Jadwal tidak valid');

            slots = data.slots;
            ready = true;
            const starts = slots.filter((slot) => slot.is_available).map((slot) => slot.start);
            setOptions(start, starts, '-- Jam Mulai --', selectedStart);
            updateEnd(selectedEnd);
            status.textContent = starts.length
                ? 'Hanya jam yang tersedia ditampilkan. Jam selesai dibatasi sebelum jadwal booking berikutnya.'
                : 'Tidak ada jam tersedia untuk ruangan pada tanggal ini. Pilih tanggal atau ruangan lain.';
        } catch {
            if (currentRequest !== requestId) return;
            ready = false;
            slots = [];
            setOptions(start, [], '-- Jam Mulai --');
            setOptions(end, [], '-- Jam Selesai --');
            submit.disabled = true;
            status.textContent = 'Jadwal gagal dimuat. Coba lagi sebelum mengirim pengajuan.';
            retry.hidden = false;
        }
    }

    facility.addEventListener('change', () => loadSchedule());
    date.addEventListener('change', () => loadSchedule());
    start.addEventListener('change', () => updateEnd());
    end.addEventListener('change', () => updateEnd());
    retry.addEventListener('click', () => loadSchedule());
    form.addEventListener('submit', (event) => {
        if (!ready || !availableEndTimes(slots, start.value).includes(end.value)) {
            event.preventDefault();
        }
    });

    return loadSchedule(start.dataset.selected, end.dataset.selected);
}
