// The @view-transition CSS rule (app.css) makes supporting browsers crossfade between full
// page loads instead of flashing to blank white. When a navigation interrupts an in-flight
// transition, the browser rejects its promise with a benign AbortError — swallow just that.
window.addEventListener('unhandledrejection', (event) => {
    if (event.reason && event.reason.name === 'AbortError') {
        event.preventDefault();
    }
});

// Scroll-reveal: fades/slides sections and cards into view as the user
// scrolls, so the page feels alive rather than a static block of content.
// Progressive enhancement only — see the `.js-reveal` gate in app.css,
// which means content stays fully visible if this never runs.
document.addEventListener('DOMContentLoaded', () => {
    const revealEls = document.querySelectorAll('[data-reveal]');
    if (!revealEls.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
        revealEls.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -60px 0px' }
    );

    revealEls.forEach((el) => observer.observe(el));
});

// Shared date/time picker state for the patient appointment booking and reschedule wizards
// (both spread this into their own x-data literal: `{ ...dcDatePicker('2026-01-01'), step: 1, ... }`)
// so the two flows never drift out of sync with two copies of the same calendar math.
window.dcDatePicker = function (todayStr, initialDate = null, initialTime = null) {
    return {
        today: todayStr,
        date: initialDate,
        time: initialTime,
        showFullCalendar: false,
        viewMonth: new Date().getMonth(),
        viewYear: new Date().getFullYear(),
        next14Days() {
            const days = [];
            const [y, m, d] = this.today.split('-').map(Number);
            const start = new Date(y, m - 1, d);
            for (let i = 0; i < 14; i++) {
                const dt = new Date(start);
                dt.setDate(start.getDate() + i);
                const iso = `${dt.getFullYear()}-${String(dt.getMonth() + 1).padStart(2, '0')}-${String(dt.getDate()).padStart(2, '0')}`;
                days.push({ iso, dayName: dt.toLocaleString('en-US', { weekday: 'short' }), dayNum: dt.getDate() });
            }
            return days;
        },
        monthLabel() {
            return new Date(this.viewYear, this.viewMonth, 1).toLocaleString('en-US', { month: 'long', year: 'numeric' });
        },
        prevMonth() { this.viewMonth--; if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; } },
        nextMonth() { this.viewMonth++; if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; } },
        daysGrid() {
            const first = new Date(this.viewYear, this.viewMonth, 1);
            const startOffset = (first.getDay() + 6) % 7; // Monday-first
            const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            const cells = [];
            for (let i = 0; i < startOffset; i++) cells.push(null);
            for (let d = 1; d <= daysInMonth; d++) cells.push(d);
            return cells;
        },
        isoFor(day) {
            const m = String(this.viewMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.viewYear}-${m}-${d}`;
        },
        isPast(day) { return this.isoFor(day) < this.today; },
        selectDate(iso) { this.date = iso; },
    };
};

// Per-viewer "saved clinics" list — front-end only (no patient_clinic_preferences backend
// wired up yet), kept in localStorage so the heart toggle on clinic cards feels real.
window.isFavoriteClinic = function (publicId) {
    try {
        const saved = JSON.parse(localStorage.getItem('dc_favorite_clinics') || '[]');
        return saved.includes(publicId);
    } catch (e) { return false; }
};
window.toggleFavoriteClinic = function (publicId) {
    try {
        const saved = JSON.parse(localStorage.getItem('dc_favorite_clinics') || '[]');
        const isSaved = saved.includes(publicId);
        const next = isSaved ? saved.filter((id) => id !== publicId) : [...saved, publicId];
        localStorage.setItem('dc_favorite_clinics', JSON.stringify(next));
        return !isSaved;
    } catch (e) { return false; }
};
