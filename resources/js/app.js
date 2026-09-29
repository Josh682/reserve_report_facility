import { initReservationSchedule } from './reservation-schedule.js';

const reservationForm = document.getElementById('reservationForm');

if (reservationForm) {
    initReservationSchedule(reservationForm);
}
