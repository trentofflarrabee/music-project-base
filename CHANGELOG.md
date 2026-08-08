# Changelog

All notable changes to Music Project Base will be documented in this file.

This project follows the Keep a Changelog structure and intends to use Semantic Versioning for production releases.

## [Unreleased]

## [1.0.0] - 2026-08-08

### Added

- Frontend templates for the homepage, posts page, single posts, pages, archives, search results, index views, and 404 pages.
- Configurable homepage presentation for Hero, Featured Content, Services, Quotes / Testimonials, Shows, Blog / News, and Newsletter sections.
- Header markup with custom-logo support, site-name branding, primary navigation, and configurable header behavior.
- Footer markup with branding, navigation, social links, tagline, copyright text, and multiple layout options.
- Responsive blog and editorial layouts with featured images, post metadata, pagination, and post navigation.
- Theme Style integration using frontend CSS variables and body classes.
- Semantic typography roles for body text, headings, editorial headings, hero headings, navigation, buttons, accent text, and quotes.
- Environmental texture presentation for supported site regions.
- A representative `screenshot.png` at the theme root.
- Translation support using the `music-project-base` text domain.
- A skip link targeting the primary content region.
- Defensive frontend fallbacks for installations where Music Project Core is temporarily inactive.

### Changed

- Frontend presentation remains owned by Music Project Base while reusable content and site configuration remain owned by Music Project Core.
- The theme uses built-in presentation defaults when Music Project Core is inactive.
- The homepage uses a curated built-in section allowlist and default section order when Core data is unavailable.
- Standard pages, posts, archives, search results, header navigation, and mobile navigation remain available when Core is inactive.
- Local stylesheet and script versions use file modification times when available for cache busting.
- Parent-theme assets load correctly when Music Project Base is used with a child theme.
- Theme Style settings are converted into curated CSS variables rather than arbitrary custom-CSS fields.
- Homepage, header, footer, editorial, and responsive styling are consolidated in the theme presentation layer.
- Blog / News “View All” links follow the WordPress Posts page when no custom destination is configured.
- Single-post back links follow the assigned WordPress Posts page and fall back safely to the site homepage when no valid Posts page exists.
- Non-paginated homepage queries avoid unnecessary row-count calculations.

### Accessibility

- Added semantic page landmarks and accessible navigation labels.
- Added keyboard-operable responsive navigation with focus containment and focus restoration.
- Added synchronized menu labels and expanded-state attributes.
- Added a functional no-JavaScript navigation fallback.
- Added visible keyboard focus treatment.
- Added reduced-motion handling for transitions, smooth scrolling, and hero video.
- Added screen-reader text for interactive controls.
- Added native WordPress language attributes and translatable frontend strings.
- Added responsive admin-bar handling and keyboard-visible skip-link behavior.