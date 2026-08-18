# Changelog

All notable changes to Music Project Base will be documented in this file.

This project follows the Keep a Changelog structure and intends to use Semantic Versioning for production releases.

## [Unreleased]

## [1.4.0] - 2026-08-18

### Added

- Added independent Compact, Standard, and Large heading presentation for Featured Content, Services, Quotes / Testimonials, Shows, Blog / News, and Newsletter homepage sections.
- Added independent responsive quote-size presentation for Featured Content and Quotes / Testimonials.
- Added optional custom homepage quote-text color presentation.
- Added semantic Heading, Link, and Text Selection color variables for Theme Style Color System v2.
- Added automatic readable foreground treatment for browser text selection.

### Changed

- Homepage section heading emphasis now remains isolated from card titles, service titles, Blog preview titles, and Hero typography.
- Featured Content quotes and Quotes / Testimonials can be emphasized independently.
- Heading Color now controls ordinary site headings separately from body Text Color.
- Link Color is limited to ordinary editorial and text-style links rather than every HTML anchor.
- Accent / Highlight Color remains dedicated to branded details such as focus states, icons, decorative accents, and editorial highlight treatments.
- Standard text-link hover behavior uses a restrained opacity treatment rather than a separate hover-color setting.
- Purpose-built navigation, buttons, linked cards, Social Links, Footer branding, and Link Hub cards retain their contextual foreground colors.
- Fixed Shows homepage markup so heading-size presentation does not interfere with event-card formatting.

## [1.3.0] - 2026-08-17

### Added

- Added Standard, Editorial Panel, and Minimal Overlay presentation styles for ordinary WordPress Pages.
- Added responsive Editorial Panel presentation with desktop image overlap and mobile image/title stacking.
- Added standalone Editorial Panel behavior for Pages without a Featured Image.
- Added automatic Standard fallback when Minimal Overlay is selected without a Featured Image.
- Added curated Compact, Standard, and Large responsive Page-title scales.
- Added Theme Style panel-tone and strength presentation for Page titles.
- Added automatic readable foreground selection for Accent-colored Page title treatments.

### Changed

- Page title treatments reuse existing Theme Style colors, typography, corners, borders, shadows, and environmental texture.
- Editorial Panel sizing and overlap were refined for stronger image/title balance on desktop.
- Minimal Overlay uses a restrained readability gradient while preserving more of the Featured Image.
- Long Page titles use more forgiving responsive wrapping and line-height behavior.
- Homepage Blog section headings now use the normal homepage/general heading role rather than Blog / Editorial heading typography.

### Accessibility

- Page Title Presentation preserves a single semantic Page `<h1>`.
- Long Page titles wrap without requiring reduced font sizes or horizontal scrolling.
- Presentation remains functional without JavaScript.
- Special WordPress Pages retain their existing template-specific presentation behavior.

## [1.2.0] - 2026-08-17

### Added

- Added specialized frontend presentation for Music Project Core's Link Hub / Link in Bio feature.
- Added assigned-Page-ID-based Link Hub template routing.
- Added a distraction-free Link Hub document shell while preserving `wp_head()`, `wp_body_open()`, `wp_footer()`, language attributes, and the WordPress admin bar.
- Added Link Hub identity presentation with Custom Logo, custom profile image, display name, and tagline support.
- Added curated theme-owned SVG icons for Link Hub destinations.
- Added featured and standard Link Hub link-card presentation.
- Added section-heading presentation and public empty-state handling.
- Added reuse of existing Music Project Social Links.
- Added optional minimal site-domain footer branding.
- Added Spotlight, Stack, and Poster Link Hub layouts.
- Added mobile-first Link Hub styling, safe-area handling, landscape behavior, visible focus states, and reduced-motion support.

### Changed

- Link Hub inherits the existing Theme Style colors, typography, corner styles, borders, shadows, and environmental texture rather than introducing a separate styling system.
- Navigation JavaScript is not loaded on the distraction-free Link Hub template.
- The assigned Link Hub Page falls back to ordinary WordPress Page presentation when Music Project Core is inactive, Link Hub is disabled, or the assignment is invalid.
- Link Hub routing remains valid when the assigned Page slug changes.

### Accessibility

- Added semantic Link Hub main content and heading structure.
- Added visible keyboard focus treatment for Link Hub cards and Social Links.
- Added decorative-icon hiding and accessible Social Link labels.
- Added touch-friendly interactive targets and responsive zoom-safe layouts.
- Added `prefers-reduced-motion` handling for Link Hub transitions.

## [1.1.0] - 2026-08-13

### Added

- Added frontend typography roles for Blog / Editorial Body and textual Site Branding.
- Added responsive Compact, Standard, and Large body-size presentation for long-form single-post content.

### Changed

- Split Navigation typography from textual site-branding typography.
- Applied the Blog / Editorial Body font family across editorial contexts while keeping the new body-size preset scoped to individual post content.
- Recalibrated the defensive texture-opacity default to match Core's stronger Standard intensity.

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