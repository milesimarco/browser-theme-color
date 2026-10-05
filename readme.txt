=== Browser Theme Color – Address Bar Color & Dark Mode ===
Contributors: Milmor
Tags: theme-color, address bar, dark mode, mobile, android
Donate link: https://www.paypal.me/milesimarco
Requires at least: 5.0
Tested up to: 7.2
Requires PHP: 7.0
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Color the mobile browser address bar with your brand color, with a separate color for dark mode.

== Description ==

Discover a powerful solution for enhancing your website's user experience across multiple platforms with our simple, lightweight, and effective plugin that adds the "theme-color" meta tag. Supported by Chrome, Edge and Samsung Internet on Android and by Safari up to iOS 18, our plugin follows the web standard to ensure a seamless user experience.

In addition to its seamless compatibility, our plugin is fully customizable, allowing you to style it to match your brand's unique identity. With the option to choose from a variety of colors, you can easily tailor the "theme-color" meta tag to perfectly complement your website's design and layout.

When you install the plugin for the first time, the color is automatically set to #23282D, which is the standard WordPress color. From there, you can easily customize the tag to match your brand, ensuring a fully immersive user experience that keeps visitors coming back for more.

= Features =

* Sets the "theme-color" meta tag used by Chrome, Edge and Samsung Internet on Android, and Safari up to iOS 18
* Optional separate color for visitors using dark mode (`prefers-color-scheme`)
* Experimental support for Safari on iOS 26+, which ignores the "theme-color" tag
* Color picker with live light/dark preview
* Developer filters: `browser_theme_color` and `browser_theme_color_dark`
* Lightweight: no external requests, no front-end scripts or styles

Development happens on GitHub: [milesimarco/browser-theme-color](https://github.com/milesimarco/browser-theme-color).

Don't settle for a subpar user experience on mobile devices. Elevate your website's design and functionality with our "theme-color" meta tag plugin today, for free!

== Installation ==
This section describes how to install the plugin and get it working.

1. Upload `browser-theme-color` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings -> Browser Theme Color
4. Choose the color you want and style it to match your style!

== Frequently Asked Questions ==

= How to set the color? =

You can set the color in the plugin settings screen (Settings -> Browser Theme Color), or via the "Settings" link in the plugins list.

= Can I use a different color in dark mode? =

Yes. Set the "Dark Mode Color" field: the plugin will output two theme-color tags with `media="(prefers-color-scheme: light)"` and `media="(prefers-color-scheme: dark)"`. Leave it empty to use a single color.

= Does it work on iPhone? =

Up to iOS 18, Safari uses the "theme-color" tag. Starting with iOS 26, Safari ignores it and tints its toolbars using the background color of your page or of fixed elements at the top and bottom edges (such as a fixed header).

Version 2.0 adds two experimental options, "Tint the top bar" and "Tint the bottom bar" (Settings -> Browser Theme Color), disabled by default. They add a thin strip in your theme color at the top and/or bottom edge of the page, only on iOS Safari, so Safari can pick up the color. Apple does not document this behavior and may change it: if your theme has a fixed header Safari may use its color instead, so test it on a real iPhone.

Tried it on an iPhone? Please share how it behaves (iOS version, theme, and whether the bars changed color) in the [support forum](https://wordpress.org/support/plugin/browser-theme-color/): your feedback will help improve this feature.

= Where did the Windows Phone and iOS web app tags go? =

Version 2.0 removed `msapplication-navbutton-color`, `apple-mobile-web-app-capable` and `apple-mobile-web-app-status-bar-style`. Windows Phone no longer exists, and the iOS tags no longer change the browser color: they only make your site open as a full-screen app when added to the iOS home screen. If you need that behavior, use a PWA plugin or add this snippet to your theme:

`add_action( 'wp_head', function() { echo '<meta name="apple-mobile-web-app-capable" content="yes">'; } );`

= Can I change the color programmatically? =

Yes, use the filters:

`add_filter( 'browser_theme_color', function( $color ) { return is_front_page() ? '#ff6600' : $color; } );`
`add_filter( 'browser_theme_color_dark', '__return_empty_string' );`

== Screenshots ==

1. Settings page: theme color, dark mode color with live previews and experimental Safari iOS 26+ options.
2. Choose any color with the WordPress color picker.

== Changelog ==

= 2.0 2026-10-05 =
* New: optional dark mode color, output with `prefers-color-scheme` media queries
* New (experimental): options to tint the top and bottom bars of Safari on iOS 26+, which ignores the theme-color tag
* New: "Settings" link in the plugins list
* New: `browser_theme_color` and `browser_theme_color_dark` filters for developers
* New: live preview with automatic text contrast in the settings page
* New: plugin options are removed on uninstall
* Improved: settings page now uses the WordPress Settings API
* Improved: meta tags are printed earlier in `<head>`
* Breaking: removed obsolete `msapplication-navbutton-color` (Windows Phone) and iOS web app meta tags (`apple-mobile-web-app-capable`, `apple-mobile-web-app-status-bar-style`), which no longer affect the browser color
* Fixed: settings menu name in readme
* Plugin name now describes what it does: "Browser Theme Color – Address Bar Color & Dark Mode"
* Tested up to WordPress 7.2
* Requires WordPress 5.0 and PHP 7.0

= 1.5 2025-05-26 =
* Tested up to latest WP
* Refactored plugin to use an object-oriented approach for better maintainability.
* Improved code security and WordPress best practices (escaping, sanitization, capability checks).
* Enhanced the settings page UI and validation.
* Integrated the color picker initialization as inline JavaScript for reliability.
* Cleaned up and modernized code style throughout the plugin.

= 1.4.1 2024-05-24 =
* Minor changes
* Tested up to latest WP

= 1.3 20230217 =
* Minor changes
* Tested up to latest WP

= 1.2.3 20220621 =
* Minor improvements
* Tested up to WP 6.0

= 1.2.2 20201002 =
* Minor improvements

= 1.2.1 20200430 =
* Compatibility check
* Minor improvements

= 1.2 05.09.2017 =
* **Tested** with WP 4.8

= 1.1 10.01.2017 =
* **Tested** with WP 4.7

= 1.0 9.06.2016 =
* First release, enjoy!