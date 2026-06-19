document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    const toggle = document.querySelector('.site-menu-toggle');
    const nav = document.querySelector('.site-nav');
    const menu = document.getElementById('primary-menu');

    if (!header || !toggle || !nav || !menu) {
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
});

(function () {
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
})();