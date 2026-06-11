// Mobile header toggle: the hamburger reveals the search bar
// (the navy subnav strip already handles navigation on mobile)
const toggle = document.querySelector('[data-nav-toggle]');
const headerSearch = document.querySelector('.header-search');

if (toggle && headerSearch) {
    toggle.setAttribute('aria-expanded', 'false');
    toggle.addEventListener('click', () => {
        const isOpen = headerSearch.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', String(isOpen));
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

// ===== City selection (cookie + dropdown + first-visit modal + geo detect) =====
const CITY_COOKIE = 'tb_city';

function setCityCookie(id) {
    document.cookie = CITY_COOKIE + '=' + id + ';path=/;max-age=31536000;samesite=lax';
}

function getCityCookie() {
    const match = document.cookie.match(/(?:^|;\s*)tb_city=(\d+)/);
    return match ? match[1] : null;
}

// Any link carrying data-city-id stores the choice before navigating
document.querySelectorAll('[data-city-id]').forEach(el => {
    el.addEventListener('click', () => setCityCookie(el.getAttribute('data-city-id')));
});

// Header dropdown
const cityPicker = document.querySelector('[data-city-picker]');
if (cityPicker) {
    cityPicker.querySelector('[data-city-toggle]')?.addEventListener('click', () => {
        cityPicker.classList.toggle('open');
    });
    document.addEventListener('click', e => {
        if (!cityPicker.contains(e.target)) cityPicker.classList.remove('open');
    });
}

// Market cities the geo lookup can resolve to
const MARKET_CITIES = [
    { id: 132, slug: '/city/dubai-132', names: ['dubai', 'sharjah', 'ajman'], country: 'AE' },
    { id: 256, slug: '/city/abu-dhabi-256', names: ['abu dhabi'], country: 'AE' },
    { id: 6, slug: '/city/las-vegas-6', names: ['las vegas', 'paradise', 'henderson'], country: 'US' },
    { id: 1, slug: '/city/new-york-1', names: ['new york', 'brooklyn', 'queens', 'newark', 'jersey city'], country: 'US' },
    { id: 2, slug: '/city/london-2', names: ['london'], country: 'GB' },
    { id: 4, slug: '/city/los-angeles-4', names: ['los angeles', 'santa monica', 'long beach', 'anaheim'], country: 'US' },
    { id: 5, slug: '/city/orlando-5', names: ['orlando', 'kissimmee'], country: 'US' },
    { id: 7, slug: '/city/san-francisco-7', names: ['san francisco', 'oakland', 'san jose'], country: 'US' },
    { id: 3, slug: '/city/miami-3', names: ['miami', 'fort lauderdale', 'hialeah'], country: 'US' },
    { id: 28, slug: '/city/toronto-28', names: ['toronto', 'mississauga', 'brampton'], country: 'CA' },
    { id: 100, slug: '/city/vancouver-100', names: ['vancouver', 'burnaby', 'surrey'], country: 'CA' },
    { id: 99, slug: '/city/montreal-99', names: ['montreal', 'laval', 'longueuil'], country: 'CA' },
    { id: 205, slug: '/city/edinburgh-205', names: ['edinburgh'], country: 'GB' },
    { id: 124, slug: '/city/rome-124', names: ['rome', 'roma'], country: 'IT' },
    { id: 126, slug: '/city/venice-126', names: ['venice', 'venezia', 'mestre'], country: 'IT' },
    { id: 123, slug: '/city/florence-123', names: ['florence', 'firenze'], country: 'IT' },
    { id: 135, slug: '/city/milan-135', names: ['milan', 'milano'], country: 'IT' },
    { id: 122, slug: '/city/barcelona-122', names: ['barcelona', 'badalona', "l'hospitalet"], country: 'ES' },
    { id: 121, slug: '/city/madrid-121', names: ['madrid', 'getafe', 'alcala de henares'], country: 'ES' },
    { id: 144, slug: '/city/seville-144', names: ['seville', 'sevilla'], country: 'ES' },
    { id: 125, slug: '/city/paris-125', names: ['paris', 'boulogne-billancourt', 'saint-denis'], country: 'FR' },
    { id: 174, slug: '/city/nice-174', names: ['nice', 'antibes'], country: 'FR' },
    { id: 22, slug: '/city/chicago-22', names: ['chicago', 'evanston', 'naperville'], country: 'US' },
    { id: 30, slug: '/city/boston-30', names: ['boston', 'cambridge ma', 'somerville'], country: 'US' },
    { id: 31, slug: '/city/seattle-31', names: ['seattle', 'bellevue', 'tacoma'], country: 'US' },
    { id: 9, slug: '/city/houston-9', names: ['houston', 'sugar land'], country: 'US' },
    { id: 16, slug: '/city/dallas-16', names: ['dallas', 'arlington', 'plano', 'irving'], country: 'US' },
    { id: 24, slug: '/city/atlanta-24', names: ['atlanta', 'marietta'], country: 'US' },
    { id: 29, slug: '/city/philadelphia-29', names: ['philadelphia', 'camden'], country: 'US' },
    { id: 14, slug: '/city/denver-14', names: ['denver', 'aurora', 'boulder'], country: 'US' },
    { id: 12, slug: '/city/phoenix-12', names: ['phoenix', 'scottsdale', 'mesa', 'tempe'], country: 'US' },
    { id: 168, slug: '/city/san-diego-168', names: ['san diego', 'chula vista'], country: 'US' },
    { id: 19, slug: '/city/new-orleans-19', names: ['new orleans', 'metairie'], country: 'US' },
    { id: 49, slug: '/city/nashville-49', names: ['nashville', 'franklin'], country: 'US' },
    { id: 58, slug: '/city/austin-58', names: ['austin', 'round rock'], country: 'US' },
    { id: 265, slug: '/city/tampa-265', names: ['tampa', 'st. petersburg', 'clearwater'], country: 'US' },
    { id: 10, slug: '/city/portland-10', names: ['portland', 'beaverton'], country: 'US' },
    { id: 18, slug: '/city/minneapolis-18', names: ['minneapolis', 'st. paul', 'saint paul'], country: 'US' },
    { id: 25, slug: '/city/detroit-25', names: ['detroit', 'dearborn'], country: 'US' },
    { id: 17, slug: '/city/san-antonio-17', names: ['san antonio'], country: 'US' },
    { id: 27, slug: '/city/charlotte-27', names: ['charlotte'], country: 'US' },
    { id: 103, slug: '/city/calgary-103', names: ['calgary', 'airdrie'], country: 'CA' },
    { id: 102, slug: '/city/ottawa-102', names: ['ottawa', 'gatineau'], country: 'CA' },
    { id: 101, slug: '/city/edmonton-101', names: ['edmonton'], country: 'CA' },
    { id: 285, slug: '/city/quebec-city-285', names: ['quebec', 'québec'], country: 'CA' },
    { id: 105, slug: '/city/winnipeg-105', names: ['winnipeg'], country: 'CA' },
    { id: 556, slug: '/city/manchester-556', names: ['manchester', 'salford', 'stockport'], country: 'GB' },
    { id: 740, slug: '/city/birmingham-740', names: ['birmingham', 'solihull', 'wolverhampton'], country: 'GB' },
    { id: 422, slug: '/city/glasgow-422', names: ['glasgow', 'paisley'], country: 'GB' },
    { id: 498, slug: '/city/liverpool-498', names: ['liverpool', 'birkenhead'], country: 'GB' },
    { id: 745, slug: '/city/leeds-745', names: ['leeds', 'bradford', 'wakefield'], country: 'GB' },
    { id: 434, slug: '/city/bristol-434', names: ['bristol'], country: 'GB' },
    { id: 814, slug: '/city/cardiff-814', names: ['cardiff', 'newport'], country: 'GB' },
    { id: 321, slug: '/city/belfast-321', names: ['belfast'], country: 'GB' },
    { id: 160, slug: '/city/naples-160', names: ['naples', 'napoli'], country: 'IT' },
    { id: 271, slug: '/city/turin-271', names: ['turin', 'torino'], country: 'IT' },
    { id: 302, slug: '/city/bologna-302', names: ['bologna'], country: 'IT' },
    { id: 303, slug: '/city/verona-303', names: ['verona'], country: 'IT' },
    { id: 411, slug: '/city/genoa-411', names: ['genoa', 'genova'], country: 'IT' },
    { id: 306, slug: '/city/palermo-306', names: ['palermo'], country: 'IT' },
    { id: 314, slug: '/city/valencia-314', names: ['valencia'], country: 'ES' },
    { id: 214, slug: '/city/malaga-214', names: ['malaga', 'málaga', 'torremolinos'], country: 'ES' },
    { id: 212, slug: '/city/granada-212', names: ['granada'], country: 'ES' },
    { id: 315, slug: '/city/bilbao-315', names: ['bilbao'], country: 'ES' },
    { id: 555, slug: '/city/alicante-555', names: ['alicante', 'benidorm'], country: 'ES' },
    { id: 401, slug: '/city/zaragoza-401', names: ['zaragoza'], country: 'ES' },
    { id: 293, slug: '/city/lyon-293', names: ['lyon', 'villeurbanne'], country: 'FR' },
    { id: 217, slug: '/city/marseille-217', names: ['marseille'], country: 'FR' },
    { id: 288, slug: '/city/bordeaux-288', names: ['bordeaux'], country: 'FR' },
    { id: 601, slug: '/city/toulouse-601', names: ['toulouse'], country: 'FR' },
    { id: 512, slug: '/city/lille-512', names: ['lille', 'roubaix'], country: 'FR' },
    { id: 513, slug: '/city/montpellier-513', names: ['montpellier'], country: 'FR' },
    { id: 290, slug: '/city/strasbourg-290', names: ['strasbourg'], country: 'FR' },
    { id: 583, slug: '/city/nantes-583', names: ['nantes'], country: 'FR' },
    { id: 291, slug: '/city/cannes-291', names: ['cannes'], country: 'FR' },
];
const COUNTRY_FALLBACK = { AE: 132, GB: 2, US: 1, CA: 28, IT: 124, ES: 122, FR: 125 };

async function detectCity() {
    const response = await fetch('https://ipapi.co/json/', { signal: AbortSignal.timeout(4500) });
    const geo = await response.json();
    const cityName = String(geo.city || '').toLowerCase();
    let match = MARKET_CITIES.find(c => c.names.some(n => cityName.includes(n)));
    if (!match && COUNTRY_FALLBACK[geo.country_code]) {
        match = MARKET_CITIES.find(c => c.id === COUNTRY_FALLBACK[geo.country_code]);
    }
    return match || null;
}

document.querySelectorAll('[data-city-detect]').forEach(btn => {
    const originalText = btn.textContent;
    btn.addEventListener('click', async () => {
        btn.disabled = true;
        btn.textContent = 'Detecting…';
        try {
            const match = await detectCity();
            if (match) {
                setCityCookie(match.id);
                window.location.reload(); // re-render the current page for the chosen city
                return;
            }
            btn.textContent = 'Not found — pick a city';
        } catch {
            btn.textContent = 'Not found — pick a city';
        }
        setTimeout(() => { btn.textContent = originalText; btn.disabled = false; }, 2500);
    });
});

// First visit: show the city picker. Location detection runs ONLY when the
// visitor presses "Detect my location" (consent — it sends their IP to ipapi.co;
// see /privacy). No silent geolocation.
const cityModal = document.querySelector('[data-city-modal]');
const openCityModal = () => {
    if (cityModal) {
        cityModal.hidden = false;
        document.body.classList.add('modal-open');
    }
};
if (!getCityCookie()) {
    openCityModal();
}
if (cityModal) {
    const dismiss = () => {
        if (!getCityCookie()) setCityCookie(cityModal.getAttribute('data-default-city') || '132');
        cityModal.hidden = true;
        document.body.classList.remove('modal-open');
    };
    cityModal.querySelector('[data-city-close]')?.addEventListener('click', dismiss);
    cityModal.addEventListener('click', e => {
        if (e.target === cityModal) dismiss();
    });
    cityModal.querySelectorAll('[data-city-id]').forEach(el => {
        el.addEventListener('click', () => {
            cityModal.hidden = true;
            document.body.classList.remove('modal-open');
        });
    });
}

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

    // Respect reduced-motion: no auto-rotation, instant (non-animated) slide moves.
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reducedMotion) track.style.transition = 'none';

    function restart() {
        if (timer) clearInterval(timer);
        if (reducedMotion) return;
        timer = setInterval(() => goTo(index + 1), 5000);
    }

    carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => { goTo(index - 1); restart(); });
    carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => { goTo(index + 1); restart(); });

    // Pause auto-rotation while hovering — and for keyboard/touch users too.
    carousel.addEventListener('mouseenter', () => { if (timer) clearInterval(timer); });
    carousel.addEventListener('mouseleave', restart);
    carousel.addEventListener('focusin', () => { if (timer) clearInterval(timer); });
    carousel.addEventListener('focusout', restart);
    carousel.addEventListener('touchstart', () => { if (timer) clearInterval(timer); }, { passive: true });

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

// ===== Infinite scroll — progressive enhancement over real pagination =====
// The server-rendered <nav class="pagination"> stays intact (crawlers + no-JS page
// normally); this just auto-appends the next page's cards as the visitor nears the
// bottom. Both work together, exactly as requested.
(function () {
    const firstPagination = document.querySelector('[data-pagination]');
    if (!firstPagination || !('IntersectionObserver' in window)) return;

    // The grid these pages append into sits just before the pagination.
    const gridFor = el => {
        let n = el ? el.previousElementSibling : null;
        while (n && !n.classList.contains('card-grid')) n = n.previousElementSibling;
        return n;
    };
    const grid = gridFor(firstPagination);
    if (!grid) return;

    let loading = false;
    let pager = firstPagination;

    const observer = new IntersectionObserver(entries => {
        if (entries.some(e => e.isIntersecting)) loadNext();
    }, { rootMargin: '700px 0px' });

    const watch = () => { if (pager.getAttribute('data-next')) observer.observe(pager); };

    async function loadNext() {
        if (loading) return;
        const next = pager.getAttribute('data-next');
        if (!next) { observer.disconnect(); return; }
        loading = true;
        observer.disconnect();
        pager.classList.add('is-loading');
        try {
            const res = await fetch(next, { headers: { 'X-Requested-With': 'fetch' } });
            if (!res.ok) throw new Error('status ' + res.status);
            const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
            const nextPager = doc.querySelector('[data-pagination]');
            const nextGrid = gridFor(nextPager) || doc.querySelector('.card-grid');
            if (nextGrid) {
                const frag = document.createDocumentFragment();
                Array.from(nextGrid.children).forEach(c => frag.appendChild(document.importNode(c, true)));
                grid.appendChild(frag);
            }
            pager.classList.remove('is-loading');
            if (nextPager) {
                const fresh = document.importNode(nextPager, true);
                pager.replaceWith(fresh);
                pager = fresh;
            } else {
                pager.removeAttribute('data-next');
            }
            try { history.replaceState(null, '', next); } catch (e) {}
        } catch (e) {
            pager.classList.remove('is-loading');
        } finally {
            loading = false;
            watch();
        }
    }

    watch();
})();

// Contact form: progressive enhancement. The form is a working plain HTML POST to
// Splitforms on its own; this intercepts it to submit via fetch so the visitor
// stays on-site and sees an inline confirmation. Any failure falls back gracefully
// (the message is shown and the form stays, so nothing is lost).
(function () {
    const form = document.querySelector('[data-contact-form]');
    if (!form) return;
    const status = form.querySelector('[data-contact-status]');
    const submit = form.querySelector('[type="submit"]');
    const say = (msg, ok) => {
        if (!status) return;
        status.textContent = msg;
        status.hidden = false;
        status.classList.toggle('is-ok', !!ok);
        status.classList.toggle('is-error', !ok);
    };
    form.addEventListener('submit', async (e) => {
        // Honeypot tripped -> silently pretend success, never hit the network.
        if (form.querySelector('[name="botcheck"]') && form.querySelector('[name="botcheck"]').checked) { e.preventDefault(); return; }
        if (!form.checkValidity()) { return; } // let the browser show native validation
        e.preventDefault();
        const label = submit ? submit.textContent : '';
        if (submit) { submit.disabled = true; submit.textContent = 'Sending…'; }
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' },
            });
            if (res.ok) {
                form.reset();
                say('Thanks — your message is on its way. We\'ll reply within one to two business days.', true);
            } else {
                say('Something went wrong sending your message. Please try again in a moment.', false);
            }
        } catch (err) {
            say('We couldn\'t reach the form service. Please check your connection and try again.', false);
        } finally {
            if (submit) { submit.disabled = false; submit.textContent = label; }
        }
    });
})();
