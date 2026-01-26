# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin called **ODR Reference Search** that provides a mineral database search interface for ODR (Open Data Repository) databases using the RRUFF structure. It features an interactive periodic table for chemistry filtering and integrates with external mineral data sources.

## Architecture

The plugin follows the WordPress Plugin Boilerplate pattern with three main layers:

- **includes/** - Core plugin logic and hook orchestration
  - `class-odr-reference-search.php` - Main plugin class that loads dependencies and registers hooks
  - `class-odr-reference-search-loader.php` - Centralized hook (action/filter) registration system
  - `class-odr-reference-search-i18n.php` - Internationalization support

- **admin/** - WordPress admin panel functionality
  - `class-odr-reference-search-admin.php` - Admin-side hook handlers
  - `partials/` - Admin UI templates

- **public/** - Frontend user-facing features
  - `class-odr-reference-search-public.php` - Registers shortcode and enqueues assets
  - `partials/odr-reference-search-public-display.php` - Search form with periodic table UI
  - `js/odr-reference-search-public.js` - Main search logic (autocomplete, element selection, form handling)

## Key Entry Points

- **odr-reference-search.php** - Plugin bootstrap, defines metadata, registers activation/deactivation hooks
- **Shortcode**: `[odr-reference-search-display]` with attributes: `datatype_id`, `general_search`, `chemistry_incl`, `mineral_name`, `sample_id`, `redirect_url`

## Development Workflow

No build tools, package managers, or test suites are configured. This is a traditional WordPress plugin:

1. Place plugin in `/wp-content/plugins/` directory
2. Activate through WordPress admin panel
3. Add shortcode `[odr-reference-search-display]` to pages/posts

## Versioning

**Important:** After making changes to the plugin, increment the version number in `odr-reference-search.php`. The version must be updated in two places:

1. The plugin header comment: `* Version: X.X.X`
2. The constant definition: `define( 'ODR_REFERENCE_SEARCH_VERSION', 'X.X.X' );`

WordPress uses this version for cache busting on enqueued assets. If the version is not incremented, WordPress may serve cached/stale CSS and JavaScript files.

## External Dependencies

- **Pure.css** - Responsive CSS framework (loaded from external ODR path)
- **jQuery Modal** (0.9.1) - Modal dialogs (bundled in `public/js/` and `public/css/`)
- **Coloris** - Color picker for admin (bundled in `admin/js/` and `admin/css/`)
- **External data files** - Mineral names and cell parameters loaded from `../../../../data-publisher/web/uploads/IMA/`

## Text Domain

Internationalization uses text domain `odr-reference-search` with translation files in `/languages/`.
