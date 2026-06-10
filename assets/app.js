// Mobile navigation toggle
const toggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');
const headerSearch = document.querySelector('.header-search');

if (toggle && nav) {
    toggle.addEventListener('click', () => {
        nav.classList.toggle('is-open');
        if (headerSearch) headerSearch.classList.toggle('is-open');
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

// Hero banner carousel (auto-rotating)
document.querySelectorAll('[data-carousel]').forEach(carousel => {
    const track = carousel.querySelector('[data-carousel-track]');
    const slides = track ? track.children : [];
    const dotsWrap = carousel.querySelector('[data-carousel-dots]');
    if (!track || slides.length < 2) return;

    let index = 0;
    let timer = null;

    if (dotsWrap) {
        for (let i = 0; i < slides.length; i++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.setAttribute('aria-label', 'Go to banner ' + (i + 1));
            dot.addEventListener('click', () => { goTo(i); restart(); });
            dotsWrap.appendChild(dot);
        }
    }

    function render() {
        track.style.transform = 'translateX(-' + (index * 100) + '%)';
        if (dotsWrap) {
            [...dotsWrap.children].forEach((dot, i) => dot.classList.toggle('active', i === index));
        }
    }

    function goTo(i) {
        index = (i + slides.length) % slides.length;
        render();
    }

    function restart() {
        if (timer) clearInterval(timer);
        timer = setInterval(() => goTo(index + 1), 5000);
    }

    carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => { goTo(index - 1); restart(); });
    carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => { goTo(index + 1); restart(); });

    // Pause auto-rotation while hovering
    carousel.addEventListener('mouseenter', () => { if (timer) clearInterval(timer); });
    carousel.addEventListener('mouseleave', restart);

    // Swipe support
    let startX = null;
    track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend', e => {
        if (startX === null) return;
        const delta = e.changedTouches[0].clientX - startX;
        if (Math.abs(delta) > 40) goTo(index + (delta < 0 ? 1 : -1));
        startX = null;
        restart();
    }, { passive: true });

    render();
    restart();
});
