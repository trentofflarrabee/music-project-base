# Music Project Base

Music Project Base is a reusable WordPress theme for bands, artists, and music projects.

It is designed to pair with the **Music Project Core** plugin.

## Requirements

- WordPress 6.8 or newer
- PHP 7.4 or newer
- Administrator access for theme setup
- Music Project Core for the complete homepage, Link Hub, and configuration experience

## Architecture

Music Project Base owns frontend presentation.

This includes:

- Theme templates
- Homepage section templates
- Header and navigation markup
- Footer markup
- Link Hub templates
- Link Hub layouts
- Link Hub icon markup
- Blog and editorial layouts
- Responsive behavior
- Frontend JavaScript
- Frontend CSS
- Accessibility presentation
- Presentation defaults

The companion Music Project Core plugin owns reusable content and site configuration.

This includes:

- Homepage settings
- Homepage section visibility and order
- Theme Style settings
- Footer settings
- Social Links
- Integrations
- Site Status
- Quotes / Testimonials
- Link Hub configuration
- Link Hub routing state
- Link Hub normalized data
- Link Hub schema and sanitization

Keeping content and presentation separate allows important configuration and editorial content to remain available when the active theme changes.

Base should not write directly to Core option arrays.

When Core may be inactive, Base should access Core functionality through guarded public APIs.

# Features

## Dynamic Homepage

The front page renders a configurable sequence of homepage sections:

- Hero
- Featured Content
- Services
- Quotes / Testimonials
- Shows
- Blog / News
- Newsletter

Music Project Core controls section visibility and order.

When Core is inactive, the theme uses its built-in default section order and avoids calling unavailable plugin functions.

## Link Hub / Link in Bio

Music Project Base provides the specialized frontend presentation layer for Music Project Core's first-party Link Hub.

When Core has Link Hub enabled and a valid WordPress Page assigned, Base renders that Page as a distraction-free artist microsite.

### Link Hub presentation includes

- Minimal dedicated document shell
- Profile image
- Custom Logo fallback
- Display name
- WordPress Site Title fallback
- Optional tagline
- Featured destination
- Standard destination cards
- Section headings
- Curated theme-owned SVG icons
- External-link indicators
- Existing Music Project Social Links
- Optional minimal domain/footer branding
- Spotlight layout
- Stack layout
- Poster layout

### Link Hub routing

Base recognizes the Link Hub using the assigned WordPress Page ID returned by Music Project Core.

It does not depend on:

```text
/links/
```

or any other hard-coded Page slug.

Changing the assigned Page permalink or slug does not break specialized Link Hub presentation.

Base special-renders the Link Hub only when:

- Music Project Core is available
- Link Hub is enabled
- Core reports a valid assigned Page
- The current request is for that assigned Page

If any of these conditions fail, WordPress uses normal Page rendering.

### Core-inactive behavior

If Music Project Core becomes inactive:

- No fatal error should occur
- The assigned WordPress Page falls back to ordinary Page presentation
- Base does not attempt to read undefined Core functions
- Base does not duplicate or cache authoritative Link Hub configuration

Reactivating Core restores specialized Link Hub rendering when its configuration remains valid.

### Link Hub layouts

#### Spotlight

The default Link Hub layout.

Characteristics include:

- Centered identity
- Larger profile treatment
- Strong Featured-link hierarchy
- Designed card depth
- Centered Social Links
- Balanced mobile-first spacing

#### Stack

A tighter, more restrained Link Hub layout.

Characteristics include:

- Left-aligned identity
- Smaller profile treatment
- Narrower content column
- Compact link cards
- Reduced card depth
- Left-aligned Social Links and footer branding

#### Poster

A more expressive editorial layout.

Characteristics include:

- Large artist typography
- Wider composition
- Stronger Featured-link treatment
- Flatter standard cards
- Section separators
- Tour-poster/editorial character

All layouts consume the same Core data.

Changing layout does not mutate or duplicate Link Hub content.

### Theme Style inheritance

Link Hub reuses the existing Music Project Theme Style system.

It inherits shared values for:

- Background
- Surface
- Text
- Muted text
- Accent
- Button background
- Button text
- Typography roles
- Corner styles
- Border strength
- Card shadows
- Environmental texture where applicable

Link Hub does not provide a second complete theme-customization system.

### Link Hub accessibility

Link Hub presentation includes:

- Semantic `<main>`
- One logical primary heading
- Ordered section headings
- Keyboard-accessible links
- Visible focus treatment
- Decorative icons hidden from assistive technology
- Accessible Social Link labels
- Responsive touch targets
- Reduced-motion support
- No hover-required interaction
- No essential animation
- No autoplaying media

### Link Hub performance

The specialized Link Hub presentation is intentionally lightweight.

It does not require frontend JavaScript for primary Link Hub functionality.

The normal responsive-navigation script is not loaded on the distraction-free Link Hub template.

Link Hub itself does not require a third-party frontend JavaScript dependency.

## Responsive Header

The theme includes:

- Custom logo support
- Site-name branding
- Primary navigation
- Responsive mobile navigation
- Keyboard-accessible menu controls
- No-JavaScript navigation fallback
- WordPress admin-bar offsets
- Standard header behavior
- Sticky header behavior
- Transparent header behavior
- Transparent-on-scroll behavior

Transparent header modes are intended primarily for the front page.

The specialized Link Hub intentionally omits the normal large site header and primary navigation.

## Footer

The normal site footer supports:

- Custom logo or site-name branding
- Footer tagline
- Footer navigation
- Social Links
- Copyright text
- Simple layout
- Stacked layout
- Split layout

Footer content is configured through Music Project Core.

When Core is inactive, the theme uses safe presentation defaults.

The specialized Link Hub does not use the full site footer. It may instead display optional minimal footer branding controlled by Core.

## Blog and Editorial Templates

The theme includes templates for:

- Posts page
- Single posts
- Pages
- Archives
- Search results
- General index views
- 404 pages

Editorial styling includes:

- Reading-width content
- Responsive post cards
- Featured images
- Post metadata
- Pagination
- Previous and next post navigation
- Blockquote styling
- WordPress block content support
- Scroll-to-top behavior on internal views

## Theme Style Integration

Music Project Base converts Music Project Core Theme Style settings into frontend CSS variables and body classes.

Supported presentation controls include:

- Background and surface colors
- Text and muted-text colors
- Accent colors
- Button colors
- Header colors
- Mobile-navigation colors
- Footer colors
- Brand display
- Header behavior
- Font-library slots
- Semantic typography roles
- Heading alignment
- Hero text styling
- Corner styles
- Card shadows
- Border strength
- Environmental texture

The theme contains built-in style defaults so normal templates remain usable when Core is inactive.

## Typography Roles

Configured font families can be assigned to semantic roles such as:

- Body text
- General headings
- Blog and editorial headings
- Hero headings
- Navigation
- Buttons and calls to action
- Accent labels and metadata
- Quotes and blockquotes

This keeps typography consistent across components without requiring a separate font control for every element.

Link Hub consumes these same semantic roles.

## Environmental Texture

A configured texture may be applied to selected visual zones:

- Desktop header
- Mobile navigation
- Footer
- Homepage sections
- Pages and posts
- Compatible editorial surfaces such as Link Hub

Texture rendering uses shared CSS variables for image, opacity, size, and repetition.

## Accessibility

The theme includes support for:

- Semantic landmarks
- Keyboard-operable mobile navigation
- Accessible navigation labels
- Screen-reader text
- Visible focus treatment
- Reduced-motion preferences
- No-JavaScript navigation behavior
- Native WordPress language attributes
- Translatable frontend strings
- Accessible Link Hub link cards
- Accessible Link Hub Social Links
- Mobile-friendly touch targets

# Installation

1. Download or clone this repository.
2. Place the theme directory at:

   ```text
   wp-content/themes/music-project-base
   ```

3. In WordPress administration, open:

   ```text
   Appearance → Themes
   ```

4. Activate **Music Project Base**.
5. Install and activate the companion Music Project Core plugin.
6. Configure the site through the **Music Project** administration menu.

# Suggested Setup Order

1. Set the site title under WordPress Settings.
2. Add a Custom Logo.
3. Create and assign the Primary Menu.
4. Create and assign the Footer Menu.
5. Configure homepage sections through Music Project Core.
6. Configure Theme Style.
7. Configure Social Links.
8. Configure the Footer.
9. Configure Link in Bio if required.
10. Review the homepage, blog, Pages, Link Hub, search results, and mobile navigation.

# Menu Locations

The theme registers two WordPress navigation locations.

## Primary Menu

Used in the normal site header and responsive mobile navigation.

Assign it under:

```text
Appearance → Menus → Manage Locations → Primary Menu
```

The specialized Link Hub does not render this navigation.

## Footer Menu

Used in the normal site footer when footer-menu display is enabled.

Assign it under:

```text
Appearance → Menus → Manage Locations → Footer Menu
```

The specialized Link Hub does not render the full Footer Menu.

Both normal menu locations use a single navigation level.

# Custom Logo

The theme supports the native WordPress Custom Logo feature.

Configure it through the site branding controls available in the current WordPress administration interface.

Music Project Core Theme Style settings determine whether the normal header displays:

- Logo and site name
- Logo only
- Site name only
- Neither

Link Hub's `Auto` profile-image mode also uses the site's Custom Logo when one is available.

# Homepage Setup

For the intended homepage experience:

1. Create a WordPress Page for the homepage.
2. Create a separate Page for blog posts.
3. Open:

   ```text
   Settings → Reading
   ```

4. Select **A static page**.
5. Assign the homepage and Posts page.
6. Configure homepage content through Music Project Core.

The theme's `front-page.php` renders the homepage section registry rather than ordinary page-editor content.

# Link Hub Setup

For the specialized Link Hub experience:

1. Activate Music Project Core.
2. Open:

   ```text
   Music Project → Link in Bio
   ```

3. Configure or assign a Link Hub Page.
4. Enable Link Hub.
5. Configure identity and presentation.
6. Add Links and Section headings.
7. Select Spotlight, Stack, or Poster.
8. Visit the assigned Page.

The Link Hub Page must not be the WordPress static Homepage or Posts page.

The Page's slug is not part of the routing contract.

# Core Dependency Behavior

Music Project Base is defensive when Music Project Core is unavailable.

With Core inactive:

- Standard Pages and posts continue to render
- Archives and search views continue to render
- Header navigation continues to work
- Mobile navigation continues to work
- Built-in Theme Style defaults are used
- The built-in homepage section order is used
- The assigned Link Hub Page uses ordinary Page presentation
- Core-managed content and settings are unavailable until the plugin is reactivated
- Saved Core data is not deleted by changing or deactivating the theme

For the complete product experience, keep Music Project Core active.

# Theme Supports

The theme registers support for:

- Automatic document titles
- Post thumbnails
- Custom logos
- HTML5 search forms
- HTML5 comment forms
- HTML5 comment lists
- HTML5 galleries
- HTML5 captions
- HTML5 style output
- HTML5 script output

# Customization Boundaries

## Use Music Project Core for

- Homepage content
- Homepage section order
- Section visibility
- Quotes / Testimonials
- Social Links
- Integrations
- Theme Style
- Footer configuration
- Site Status
- Link Hub configuration
- Link Hub Page assignment
- Link Hub links and sections
- Link Hub identity
- Link Hub layout selection

## Use WordPress for

- Site title
- Site tagline
- Custom Logo
- Posts
- Pages
- Featured images
- Navigation menus
- Reading settings
- Media Library assets

## Use Music Project Base for

- Template markup
- Link Hub markup
- Responsive layouts
- Component styling
- Frontend JavaScript
- Accessibility behavior
- Theme presentation defaults
- Curated icon markup

Avoid storing reusable editorial content directly in theme files.

Avoid adding site-specific settings to the theme when they belong in Music Project Core.

For project-specific PHP or template overrides, use a child theme rather than editing a released copy of Music Project Base directly.

# Template Structure

Important theme files include:

```text
style.css
functions.php
header.php
footer.php
front-page.php
home.php
single.php
page.php
link-hub.php
archive.php
search.php
404.php
index.php
```

Reusable theme helpers are stored in:

```text
inc/
```

Link Hub routing and presentation helpers are stored in:

```text
inc/link-hub.php
```

Reusable frontend components are stored in:

```text
template-parts/
```

Homepage section templates are stored in:

```text
template-parts/home/
```

Frontend scripts are stored in:

```text
assets/js/
```

# Link Hub Template Behavior

`link-hub.php` is a theme-owned specialized template selected through guarded Link Hub routing.

It retains normal WordPress document fundamentals, including:

```text
language_attributes()
wp_head()
wp_body_open()
body_class()
wp_footer()
```

It intentionally does not call the normal full site:

```text
get_header()
get_footer()
```

because Link Hub is designed as a distraction-free microsite.

If the guarded Link Hub conditions are not satisfied, normal Page rendering remains authoritative.

# Asset Loading

The theme loads:

- The main `style.css` stylesheet
- Theme Style inline CSS variables
- The responsive-navigation script on normal navigation-bearing templates
- An optional configured Google Fonts stylesheet

The navigation script is intentionally skipped on the specialized Link Hub request because Link Hub does not render the normal site navigation.

Local asset versions use file modification times when available for automatic cache busting.

The theme version is used as a fallback asset version.

# Translation

The theme uses the text domain:

```text
music-project-base
```

Local translation files may be placed in:

```text
languages/
```

# Screenshot

The theme screenshot is stored at the root of the theme:

```text
screenshot.png
```

Project target dimensions:

```text
1200 × 900 pixels
```

The screenshot should show a representative homepage view and should not include browser chrome, administration controls, private information, or unrelated third-party trademarks.

# Packaging

Create a release ZIP from a clean, committed checkout of the intended release branch:

```bash
git archive \
  --format=zip \
  --prefix=music-project-base/ \
  --output=../music-project-base.zip \
  HEAD
```

The release ZIP must contain one top-level directory:

```text
music-project-base/
```

The archive should include these production files and directories:

```text
music-project-base/
├── assets/
├── inc/
├── template-parts/
├── 404.php
├── CHANGELOG.md
├── LICENSE
├── README.md
├── archive.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── home.php
├── index.php
├── link-hub.php
├── page.php
├── screenshot.png
├── search.php
├── single.php
└── style.css
```

Install and test the generated ZIP on a clean WordPress site before publishing it.

# Development

The theme uses WordPress-native APIs and conventions, including:

- Theme support registration
- Navigation menu locations
- Template hierarchy
- Template parts
- Template filters
- Translation functions
- Body classes
- Custom Logo functions
- Asset enqueue functions
- Inline CSS variables
- Featured-image functions
- Responsive image APIs
- `wp_head()`
- `wp_body_open()`
- `wp_footer()`

The theme does not provide a page builder or arbitrary custom-CSS settings layer.

Presentation controls are intentionally curated through Music Project Core.

# Link Hub Development Boundary

Music Project Base should consume Link Hub data only through Core's documented public API.

Relevant Core functions include:

```php
mpc_get_link_hub_settings()
mpc_get_link_hub_setting()
mpc_get_link_hub_items()
mpc_get_link_hub_url()
mpc_is_link_hub_enabled()
mpc_get_link_hub_page_id()
```

Calls must be guarded with `function_exists()` where Core may be unavailable.

Base must not write directly to:

```text
mpc_link_hub_settings
```

Base owns actual icon SVG markup.

Core owns the curated icon keys.

Base should treat Core-provided Link Hub data as rendering-neutral content and continue escaping values at output time.

# Responsive Link Hub QA

Primary Link Hub QA widths include:

```text
320px
375px
390px
430px
768px
desktop
```

Frontend checks should include:

- No horizontal overflow
- Long artist names
- Long link labels
- Long subtitles
- Featured cards
- Section headings
- Social-link wrapping
- Safe-area spacing
- Logged-in WordPress admin bar
- Landscape phone orientation
- Browser zoom
- Keyboard-only navigation
- Reduced-motion preference

# Privacy

Music Project Base does not add product analytics or telemetry.

The Link Hub template does not require third-party JavaScript.

External destinations and Social Links do not load third-party content merely because the Link Hub page is viewed.

Normal visitor navigation to an external URL may naturally contact that external service.

# Companion Plugin

Music Project Core:

```text
https://github.com/trentofflarrabee/music-project-core
```

# Support and Issues

Report reproducible theme problems through:

```text
https://github.com/trentofflarrabee/music-project-base/issues
```

Include WordPress version, PHP version, Music Project Core version, reproduction steps, affected template/Page, relevant PHP/browser errors, browser/viewport details for responsive issues, and Link Hub layout where applicable.

# Version

Current version:

```text
1.2.0
```

# License

Music Project Base is licensed under the GNU General Public License, version 2 or any later version.

SPDX license identifier:

```text
GPL-2.0-or-later
```

See `LICENSE` for the complete license terms.
