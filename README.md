Music Project Base
==================

Music Project Base is a reusable WordPress theme for bands, artists, and music projects.

It is designed to pair with the [Music Project Core](https://github.com/trentofflarrabee/music-project-core) plugin.

Requirements
------------

-   WordPress 6.8 or newer
-   PHP 7.4 or newer
-   Administrator access for theme setup
-   Music Project Core for the complete homepage and configuration experience

Architecture
------------

Music Project Base owns frontend presentation.

This includes:

-   Theme templates
-   Homepage section templates
-   Header and navigation markup
-   Footer markup
-   Blog and editorial layouts
-   Responsive behavior
-   Frontend JavaScript
-   Frontend CSS

The companion Music Project Core plugin owns reusable content and site configuration.

This includes:

-   Homepage settings
-   Homepage section visibility and order
-   Theme Style settings
-   Footer settings
-   Social links
-   Integrations
-   Site Status
-   Quotes / Testimonials

Keeping content and presentation separate allows important configuration and editorial content to remain available when the active theme changes.

Features
--------

### Dynamic Homepage

The front page renders a configurable sequence of homepage sections:

-   Hero
-   Featured Content
-   Services
-   Quotes / Testimonials
-   Shows
-   Blog / News
-   Newsletter

Music Project Core controls section visibility and order.

When Core is inactive, the theme uses its built-in default section order and avoids calling unavailable plugin functions.

### Responsive Header

The theme includes:

-   Custom logo support
-   Site-name branding
-   Primary navigation
-   Responsive mobile navigation
-   Keyboard-accessible menu controls
-   No-JavaScript navigation fallback
-   WordPress admin-bar offsets
-   Standard, Sticky, Transparent, and Transparent on Scroll behaviors

Transparent header modes are intended primarily for the front page.

### Footer

The footer supports:

-   Custom logo or site-name branding
-   Footer tagline
-   Footer navigation
-   Social links
-   Copyright text
-   Simple, Stacked, and Split layouts

Footer content is configured through Music Project Core.

When Core is inactive, the theme uses safe presentation defaults.

### Blog and Editorial Templates

The theme includes templates for:

-   Posts page
-   Single posts
-   Pages
-   Archives
-   Search results
-   General index views
-   404 pages

Editorial styling includes:

-   Reading-width content
-   Responsive post cards
-   Featured images
-   Post metadata
-   Pagination
-   Previous and next post navigation
-   Blockquote styling
-   WordPress block content support
-   Scroll-to-top behavior on internal views

### Theme Style Integration

Music Project Base converts Music Project Core Theme Style settings into frontend CSS variables and body classes.

Supported presentation controls include:

-   Background and surface colors
-   Text and muted-text colors
-   Accent colors
-   Button colors
-   Header colors
-   Mobile-navigation colors
-   Footer colors
-   Brand display
-   Header behavior
-   Font-library slots
-   Semantic typography roles
-   Heading alignment
-   Hero text styling
-   Corner styles
-   Card shadows
-   Border strength
-   Environmental texture

The theme contains built-in style defaults so normal templates remain usable when Core is inactive.

### Typography Roles

Configured font families can be assigned to semantic roles such as:

-   Body text
-   General headings
-   Blog and editorial headings
-   Hero headings
-   Navigation
-   Buttons and calls to action
-   Accent labels and metadata
-   Quotes and blockquotes

This keeps typography consistent across components without requiring a separate font control for every element.

### Environmental Texture

A configured texture may be applied to selected visual zones:

-   Desktop header
-   Mobile navigation
-   Footer
-   Homepage sections
-   Pages and posts

Texture rendering uses shared CSS variables for image, opacity, size, and repetition.

### Accessibility

The theme includes support for:

-   Semantic landmarks
-   Keyboard-operable mobile navigation
-   Accessible navigation labels
-   Screen-reader text
-   Visible focus treatment
-   Reduced-motion preferences
-   No-JavaScript navigation behavior
-   Native WordPress language attributes
-   Translatable frontend strings

Installation
------------

1.  Download or clone this repository.
2.  Place the theme directory at:

    ```
    wp-content/themes/music-project-base
    ```

3.  In WordPress administration, open:

    ```
    Appearance → Themes
    ```

4.  Activate **Music Project Base**.
5.  Install and activate the companion Music Project Core plugin.
6.  Configure the site through the **Music Project** administration menu.

Suggested Setup Order
---------------------

1.  Set the site title under WordPress Settings.
2.  Add a custom logo.
3.  Create and assign the Primary Menu.
4.  Create and assign the Footer Menu.
5.  Configure homepage sections through Music Project Core.
6.  Configure Theme Style.
7.  Configure Social Links.
8.  Configure the Footer.
9.  Review the homepage, blog, pages, search results, and mobile navigation.

Menu Locations
--------------

The theme registers two WordPress navigation locations.

### Primary Menu

Used in the site header and responsive mobile navigation.

Assign it under:

```
Appearance → Menus → Manage Locations → Primary Menu
```

### Footer Menu

Used in the site footer when footer-menu display is enabled.

Assign it under:

```
Appearance → Menus → Manage Locations → Footer Menu
```

Both menu locations use a single navigation level.

Custom Logo
-----------

The theme supports the native WordPress Custom Logo feature.

Configure it under the site branding controls available in the current WordPress administration interface.

Music Project Core Theme Style settings determine whether the header displays:

-   Logo and site name
-   Logo only
-   Site name only
-   Neither

Homepage Setup
--------------

For the intended homepage experience:

1.  Create a WordPress page for the homepage.
2.  Create a separate page for blog posts.
3.  Open:

    ```
    Settings → Reading
    ```

4.  Select **A static page**.
5.  Assign the homepage and posts page.
6.  Configure homepage content through Music Project Core.

The theme's `front-page.php` renders the homepage section registry rather than ordinary page-editor content.

Core Dependency Behavior
------------------------

Music Project Base is defensive when Music Project Core is unavailable.

With Core inactive:

-   Standard pages and posts continue to render.
-   Archives and search views continue to render.
-   Header navigation continues to work.
-   Mobile navigation continues to work.
-   Built-in Theme Style defaults are used.
-   The built-in homepage section order is used.
-   Core-managed content and settings are unavailable until the plugin is reactivated.
-   Saved Core data is not deleted by changing or deactivating the theme.

For the complete product experience, keep Music Project Core active.

Theme Supports
--------------

The theme registers support for:

-   Automatic document titles
-   Post thumbnails
-   Custom logos
-   HTML5 search forms
-   HTML5 comment forms
-   HTML5 comment lists
-   HTML5 galleries
-   HTML5 captions
-   HTML5 style output
-   HTML5 script output

Customization Boundaries
------------------------

### Use Music Project Core for

-   Homepage content
-   Homepage section order
-   Section visibility
-   Quotes / Testimonials
-   Social links
-   Integrations
-   Theme Style
-   Footer configuration
-   Site Status

### Use WordPress for

-   Site title
-   Tagline
-   Custom logo
-   Posts
-   Pages
-   Featured images
-   Navigation menus
-   Reading settings

### Use Music Project Base for

-   Template markup
-   Responsive layouts
-   Component styling
-   Frontend JavaScript
-   Accessibility behavior
-   Theme presentation defaults

Avoid storing reusable editorial content directly in theme files.

Avoid adding site-specific settings to the theme when they belong in Music Project Core.

For project-specific PHP or template overrides, use a child theme rather than editing a released copy of Music Project Base directly.

Template Structure
------------------

Important theme files include:

```
style.css
functions.php
header.php
footer.php
front-page.php
home.php
single.php
page.php
archive.php
search.php
404.php
index.php
```

Reusable theme helpers are stored in:

```
inc/
```

Reusable frontend components are stored in:

```
template-parts/
```

Homepage section templates are stored in:

```
template-parts/home/
```

Frontend scripts are stored in:

```
assets/js/
```

Asset Loading
-------------

The theme loads:

-   The main `style.css` stylesheet
-   Theme Style inline CSS variables
-   The responsive-navigation script
-   An optional configured Google Fonts stylesheet

Local asset versions use file modification times when available for automatic cache busting.

The theme version is used as a fallback asset version.

Translation
-----------

The theme uses the text domain:

```
music-project-base
```

Local translation files may be placed in:

```
languages/
```

Screenshot
----------

Before public packaging, add this file to the root of the theme:

```
screenshot.png
```

Project target dimensions:

```
1200 × 900 pixels
```

The screenshot should show a representative homepage view and should not include browser chrome, administration controls, private information, or third-party trademarks that are not part of the site.

Packaging
---------

A release ZIP should contain one top-level directory:

```
music-project-base/
```

The ZIP should include production theme files such as:

```
music-project-base/
├── assets/
├── inc/
├── template-parts/
├── 404.php
├── archive.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── home.php
├── index.php
├── page.php
├── README.md
├── screenshot.png
├── search.php
├── single.php
└── style.css
```

Do not include development-only files or directories such as:

```
.git/
.github/
.DS_Store
Thumbs.db
node_modules/
vendor/
IDE project files
local database exports
environment files
```

Development
-----------

The theme uses WordPress-native APIs and conventions, including:

-   Theme support registration
-   Navigation menu locations
-   Template hierarchy
-   Template parts
-   Translation functions
-   Body classes
-   Custom logo functions
-   Asset enqueue functions
-   Inline CSS variables
-   Featured-image functions

The theme does not provide a page builder or arbitrary custom-CSS settings layer.

Presentation controls are intentionally curated through Music Project Core.

Companion Plugin
----------------

Music Project Core:

```
https://github.com/trentofflarrabee/music-project-core
```

Support and Issues
------------------

Report reproducible theme problems through the repository issue tracker:

```
https://github.com/trentofflarrabee/music-project-base/issues
```

Include:

-   WordPress version
-   PHP version
-   Music Project Core version
-   Steps to reproduce
-   Template or page affected
-   Relevant PHP errors
-   Relevant browser-console errors
-   Browser and viewport information for responsive issues

Version
-------

Current development version:

```
0.1.0
```

## License

Music Project Base is licensed under the GNU General Public License, version 2 or any later version.

SPDX license identifier: `GPL-2.0-or-later`.

See [LICENSE](LICENSE) for the complete license terms.