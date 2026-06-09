// Mobile navigation toggle
const toggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');
const headerSearch = document.querySelector('.header-search');

if (toggle && nav && headerSearch) {
    toggle.addEventListener('click', () => {
        nav.classList.toggle('is-open');
        headerSearch.classList.toggle('is-open');
    });
}

// Active navigation page indicator
const currentPath = window.location.pathname;
document.querySelectorAll('.site-nav a').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPath || (currentPath.startsWith(href) && href !== '/')) {
        link.classList.add('active');
    }
});

// Horizontal rail smooth-scroll buttons
document.querySelectorAll('.rail-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const direction = parseInt(btn.getAttribute('data-scroll-dir') || '1');
        const wrapper = btn.closest('.rail-wrapper');
        if (wrapper) {
            const rail = wrapper.querySelector('.rail');
            if (rail) {
                // Scroll by 80% of rail viewport width
                const scrollAmount = rail.clientWidth * 0.8;
                rail.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
            }
        }
    });
});
