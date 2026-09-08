# Change log

## [[1.1.2]](https://github.com/lightspeedwp/lsx-business-directory/releases/tag/1.1.2) - Unreleased

### Fixed
- The WooCommerce listing form script was enqueued but never compiled, so `assets/js/lsx-bd-listing-form.min.js` 404'd on the front end. The build now emits it.
- The admin script enqueue pointed at `lsx-business-directory-admin.min.js`, which has never existed in this repository. It now points at `lsx-bd-admin.min.js`, the file the admin source actually compiles to.

### Changed
- Rebuilt the asset pipeline on gulp 5. The previous gulpfile used gulp 3 task syntax and had not run since gulp 4 landed, so no asset change could be compiled.
- Replaced gulp-uglify with gulp-terser, gulp-autoprefixer with gulp-postcss + autoprefixer, and gulp-sourcemaps with gulp 5's built-in sourcemaps. Dropped gulp-util, gulp-jshint, jshint, gulp-minify-css and gulp-concat, none of which were still in use.
- CSS is now compiled compressed rather than compact; dart-sass does not support the compact output style. The rules are unchanged.
- Node pinned to 24.20.0, the current LTS line.

### Removed
- The `dependencies` block. acorn, clean-css, extend, fstream, lodash, lodash.template, minimatch, minimist, set-value, shelljs and tar were never imported by any source file - they were leftover `npm audit fix` pins, and clearing them resolves both Dependabot alerts, including `lodash.template <= 4.5.0`, which has no patched version.
- Stale `lsx-starter-plugin` sourcemaps left over from the plugin this was forked from.

## [[1.1.1]](https://github.com/lightspeeddevelopment/lsx-business-directory/releases/tag/1.1.1) - Unreleased

### Added
- Enabled the use of the block editor for the TO post types descriptions.
- The listings now have a status drop down to allow you to change your listing from published to pending without affecting the subscription status.

### Changed
- Removed the LSX Search integration class, as LSX Search now handles the post types.

### Security
- General testing to ensure compatibility with latest WordPress version (5.6)

## [[1.1.0]](https://github.com/lightspeeddevelopment/lsx-business-directory/releases/tag/1.1.0) - 19 May 2020

### Added
- Added in a "Translations" settings tab to allow the translation of the post type and taxonomy slugs.
- Added Industries shortcode to the Listing archive.
- Added in a setting to control the display of the excerpt on the archive and the search pages.
- Added in a setting to the shortcode to toggle the display of the excerpts.
- Added Industries shortcode to the Industries taxonomy archive.
- Added dynamic links depending on the slug for the listing metadata.
- Added in a Listings page to the WooCommerce My Account page.
- Added styling for listing forms.
- Added in integration for the WooCommerce Subscriptions plugin.

### Changed
- Refactored layout templates tags.

### Fixed
- Removing the `using-gutenberg` body class when the listing is using blocks.
- Removing the Bootstrap column classes breaking the padding of the single listings on mobile.
- Fixing the button styling to allow the customizer colours.


## [[1.0.0]](https://github.com/lightspeeddevelopment/lsx-business-directory/releases/tag/1.0.0) - 30 March 2020
Initial release
