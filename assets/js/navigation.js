document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');

    if (header) {
        initHeaderScrollState(header);
        initMobileNavigation(header);
    }

    initScrollTopButton();
});

function initHeaderScrollState(header) {
    const shouldTrackScroll = document.body.classList.contains('mpb-header-transparent-scroll');

    if (!shouldTrackScroll) {
        return;
    }

    let ticking = false;

    const updateHeaderState = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 24);
        ticking = false;
    };

    const requestUpdate = () => {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(updateHeaderState);
    };

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);

    updateHeaderState();
}

function initMobileNavigation(header) {
    const toggle = document.querySelector('.site-menu-toggle');
    const nav = document.querySelector('.site-nav');
    const menu = document.getElementById('primary-menu');

    if (!toggle || !nav || !menu) {
        return;
    }

    const openMenu = () => {
        header.classList.add('is-menu-open');
        document.body.classList.add('is-menu-open');
        toggle.setAttribute('aria-expanded', 'true');
    };

    const closeMenu = () => {
        header.classList.remove('is-menu-open');
        document.body.classList.remove('is-menu-open');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const isOpen = () => header.classList.contains('is-menu-open');

    toggle.addEventListener('click', () => {
        if (isOpen()) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            closeMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) {
            closeMenu();
            toggle.focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (!isOpen()) {
            return;
        }

        const clickedInsideHeader = header.contains(event.target);

        if (!clickedInsideHeader) {
            closeMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 720 && isOpen()) {
            closeMenu();
        }
    });
}

function initScrollTopButton() {
    const scrollTopButton = document.querySelector('.scroll-top');

    if (!scrollTopButton) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    scrollTopButton.hidden = false;

    const toggleScrollTopButton = () => {
        scrollTopButton.classList.toggle('is-visible', window.scrollY > 600);
    };

    scrollTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: prefersReducedMotion.matches ? 'auto' : 'smooth',
        });
    });

    window.addEventListener('scroll', toggleScrollTopButton, { passive: true });

    toggleScrollTopButton();
}