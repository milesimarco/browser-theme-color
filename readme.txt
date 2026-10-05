=== Browser Theme Color ===
Contributors: Milmor
Tags: browser, theme, color, android, mobile
Donate link: https://www.paypal.me/milesimarco
Requires at least: 5.0
Tested up to: 7.2
Requires PHP: 7.0
Stable tag: 1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add the 'theme-color' meta tag to your website for a seamless user experience on Android & iOS with our easy-to-use plugin.

== Description ==

Discover a powerful solution for enhancing your website's user experience across multiple platforms with our simple, lightweight, and effective plugin that adds the "theme-color" meta tag. Compatible with Android, iOS, and Windows Phone, our plugin is designed based on Google guidelines to ensure a seamless user experience on all devices.

In addition to its seamless compatibility, our plugin is fully customizable, allowing you to style it to match your brand's unique identity. With the option to choose from a variety of colors, you can easily tailor the "theme-color" meta tag to perfectly complement your website's design and layout.

When you install the plugin for the first time, the color is automatically set to #23282D, which is the standard WordPress color. From there, you can easily customize the tag to match your brand, ensuring a fully immersive user experience that keeps visitors coming back for more.

= Features =

* Sets the "theme-color" meta tag used by Chrome, Edge, Safari and Samsung Internet
* Optional separate color for visitors using dark mode (`prefers-color-scheme`)
* Color picker with live light/dark preview
* Optional legacy Windows Phone and iOS web app meta tags
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

= What are the legacy tags? =

`msapplication-navbutton-color` (Windows Phone), `apple-mobile-web-app-capable` and `apple-mobile-web-app-status-bar-style` (iOS). They are enabled by default for backward compatibility. Note that `apple-mobile-web-app-capable` makes your site open as a full-screen web app when added to the iOS home screen: disable the option if you don't want that.

= Can I change the color programmatically? =

Yes, use the filters:

`add_filter( 'browser_theme_color', function( $color ) { return is_front_page() ? '#ff6600' : $color; } );`
`add_filter( 'browser_theme_color_dark', '__return_empty_string' );`

== Screenshots ==

1. Theme example in Android multitasking [developers.google.com](https://developers.google.com/web/fundamentals/design-and-ui/browser-customization/theme-color)
2. Topbar example in Android Chrome [developers.google.com](https://developers.google.com/web/fundamentals/design-and-ui/browser-customization/theme-color)

== Changelog ==

= 1.6 2026-10-05 =
* New: optional dark mode color, output with `prefers-color-scheme` media queries
* New: option to disable the legacy Windows Phone / iOS web app meta tags
* New: "Settings" link in the plugins list
* New: `browser_theme_color` and `browser_theme_color_dark` filters for developers
* New: live preview with automatic text contrast in the settings page
* New: plugin options are removed on uninstall
* Improved: settings page now uses the WordPress Settings API
* Improved: meta tags are printed earlier in `<head>`
* Fixed: settings menu name in readme
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