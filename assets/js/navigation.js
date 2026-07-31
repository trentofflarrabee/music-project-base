document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector(".site-header");

  if (header) {
    initHeaderScrollState(header);
    initMobileNavigation(header);
  }

  initScrollTopButton();
  initHeroVideoMotionPreference();
});

function initHeaderScrollState(header) {
  const shouldTrackScroll = document.body.classList.contains(
    "mpb-header-transparent-scroll",
  );

  if (!shouldTrackScroll) {
    return;
  }

  let ticking = false;

  const updateHeaderState = () => {
    header.classList.toggle("is-scrolled", window.scrollY > 24);
    ticking = false;
  };

  const requestUpdate = () => {
    if (ticking) {
      return;
    }

    ticking = true;
    window.requestAnimationFrame(updateHeaderState);
  };

  window.addEventListener("scroll", requestUpdate, {
    passive: true,
  });

  window.addEventListener("resize", requestUpdate);

  updateHeaderState();
}

function initMobileNavigation(header) {
  const toggle = header.querySelector(".site-menu-toggle");
  const nav = header.querySelector(".site-nav");

  if (!toggle || !nav) {
    return;
  }

  const mobileViewport = window.matchMedia("(max-width: 720px)");
  const screenReaderText = toggle.querySelector(".screen-reader-text");

  const existingLabel = screenReaderText
    ? String(screenReaderText.textContent || "").trim()
    : "";

  const openLabel = toggle.dataset.openLabel || existingLabel;

  const closeLabel = toggle.dataset.closeLabel || openLabel;
  const focusableSelector = [
    "a[href]",
    "button:not([disabled])",
    "input:not([disabled])",
    "select:not([disabled])",
    "textarea:not([disabled])",
    '[tabindex]:not([tabindex="-1"])',
  ].join(",");

  let previouslyFocused = null;
  let focusFrame = null;

  const isOpen = () => header.classList.contains("is-menu-open");

  const isVisibleFocusableElement = (element) => {
    if (!(element instanceof HTMLElement)) {
      return false;
    }

    if (
      element.hasAttribute("disabled") ||
      element.getAttribute("aria-hidden") === "true"
    ) {
      return false;
    }

    return element.getClientRects().length > 0;
  };

  const getNavFocusableElements = () => {
    return Array.from(nav.querySelectorAll(focusableSelector)).filter(
      isVisibleFocusableElement,
    );
  };

  const getFocusTrapElements = () => {
    return [toggle, ...getNavFocusableElements()].filter(
      isVisibleFocusableElement,
    );
  };

  const updateToggleLabel = (label) => {
    if (screenReaderText) {
      screenReaderText.textContent = label;
    }
  };

  const syncAccessibilityState = () => {
    const isMobile = mobileViewport.matches;
    const menuIsOpen = isMobile && isOpen();

    toggle.setAttribute("aria-expanded", menuIsOpen ? "true" : "false");

    updateToggleLabel(menuIsOpen ? closeLabel : openLabel);

    if (isMobile) {
      nav.setAttribute("aria-hidden", menuIsOpen ? "false" : "true");

      nav.inert = !menuIsOpen;
    } else {
      nav.removeAttribute("aria-hidden");
      nav.inert = false;
    }
  };

  const openMenu = () => {
    if (!mobileViewport.matches || isOpen()) {
      return;
    }

    previouslyFocused =
      document.activeElement instanceof HTMLElement
        ? document.activeElement
        : toggle;

    header.classList.add("is-menu-open");
    document.body.classList.add("is-menu-open");

    syncAccessibilityState();

    if (focusFrame) {
      window.cancelAnimationFrame(focusFrame);
    }

    focusFrame = window.requestAnimationFrame(() => {
      const navFocusableElements = getNavFocusableElements();
      const firstNavControl = navFocusableElements[0] || toggle;

      firstNavControl.focus();
      focusFrame = null;
    });
  };

  const closeMenu = ({ restoreFocus = true } = {}) => {
    if (focusFrame) {
      window.cancelAnimationFrame(focusFrame);
      focusFrame = null;
    }

    header.classList.remove("is-menu-open");
    document.body.classList.remove("is-menu-open");

    syncAccessibilityState();

    const focusTarget = previouslyFocused;
    previouslyFocused = null;

    if (
      restoreFocus &&
      focusTarget instanceof HTMLElement &&
      document.contains(focusTarget)
    ) {
      window.requestAnimationFrame(() => {
        focusTarget.focus();
      });
    }
  };

  toggle.addEventListener("click", () => {
    if (isOpen()) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  nav.addEventListener("click", (event) => {
    if (event.target.closest("a")) {
      closeMenu();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (!mobileViewport.matches || !isOpen()) {
      return;
    }

    if (event.key === "Escape") {
      event.preventDefault();
      closeMenu();
      return;
    }

    if (event.key !== "Tab") {
      return;
    }

    const focusableElements = getFocusTrapElements();

    if (!focusableElements.length) {
      event.preventDefault();
      toggle.focus();
      return;
    }

    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    if (!focusableElements.includes(document.activeElement)) {
      event.preventDefault();
      firstElement.focus();
      return;
    }

    if (event.shiftKey && document.activeElement === firstElement) {
      event.preventDefault();
      lastElement.focus();
      return;
    }

    if (!event.shiftKey && document.activeElement === lastElement) {
      event.preventDefault();
      firstElement.focus();
    }
  });

  document.addEventListener("click", (event) => {
    if (!isOpen() || header.contains(event.target)) {
      return;
    }

    closeMenu();
  });

  const handleViewportChange = () => {
    if (!mobileViewport.matches && isOpen()) {
      closeMenu({
        restoreFocus: false,
      });
    }

    syncAccessibilityState();
  };

  if (typeof mobileViewport.addEventListener === "function") {
    mobileViewport.addEventListener("change", handleViewportChange);
  } else {
    mobileViewport.addListener(handleViewportChange);
  }

  syncAccessibilityState();
}

/**
 * Respect the visitor's reduced-motion preference for hero videos.
 *
 * CSS immediately replaces the video with the configured hero image.
 * JavaScript also pauses the hidden video so it is not continuing to
 * animate or decode unnecessarily in the background.
 */
function initHeroVideoMotionPreference() {
  const videos = Array.from(document.querySelectorAll(".home-hero__video"));

  if (!videos.length) {
    return;
  }

  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

  const syncVideoState = () => {
    videos.forEach((video) => {
      if (!(video instanceof HTMLVideoElement)) {
        return;
      }

      if (reducedMotion.matches) {
        video.pause();
        video.autoplay = false;
        video.removeAttribute("autoplay");
        return;
      }

      video.muted = true;
      video.autoplay = true;
      video.setAttribute("autoplay", "");

      const playPromise = video.play();

      if (playPromise && typeof playPromise.catch === "function") {
        /*
         * Autoplay may still be blocked by a browser policy.
         * The configured poster/image remains available.
         */
        playPromise.catch(() => {});
      }
    });
  };

  if (typeof reducedMotion.addEventListener === "function") {
    reducedMotion.addEventListener("change", syncVideoState);
  } else {
    reducedMotion.addListener(syncVideoState);
  }

  syncVideoState();
}

function initScrollTopButton() {
  const scrollTopButton = document.querySelector(".scroll-top");

  if (!scrollTopButton) {
    return;
  }

  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  );

  scrollTopButton.hidden = false;

  const toggleScrollTopButton = () => {
    scrollTopButton.classList.toggle("is-visible", window.scrollY > 600);
  };

  scrollTopButton.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: prefersReducedMotion.matches ? "auto" : "smooth",
    });
  });

  window.addEventListener("scroll", toggleScrollTopButton, {
    passive: true,
  });

  toggleScrollTopButton();
}
