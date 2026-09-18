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
