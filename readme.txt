https://wordpress.org/plugins/custom-sub-menus/

=== Plugin Name ===
Contributors: gataf
Tags: custom menus, sub menus
Requires at least: 3.0.1
Tested up to: 5.0.1
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to add a custom menu to individual pages.

== Description ==

This plugin allows you to add a custom "sub" menu to individual pages. Where WordPress only really allows for one "sub" menu to be added across all pages, this plugin allows you to have a different menu for each individual page.

If no menu is selected, will use the child pages by default. If no child pages, will list all top-level pages.

== Installation ==

Installation

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

Usage

1. Head to the 'Widgets' screen and add a Text widget to your Widget Area. In that text widget, enter the shortcode, `[the_custom_menu]`.
1. If you haven't created any custom menus yet, navigate to the 'Menus' screen and create your menu.
1. Go to the 'Pages' screen and you can either go to the page you wish you edit, use Quick Edit or if you need to edit a bunch of pages, use Bulk Edit. From there, select the custom menu from the new 'Menus' dropdown. Be sure to save.

== Changelog ==

= 1.3.3 =
* Fixed issues where it was breaking the backend page rows.

= 1.3.2 =
* Fixed issue where custom column results were displaying in other columns.

= 1.3.1 =
* Fixed issue where all top level pages were displaying on a parent page instead of the child pages.

= 1.3 =
* Fixed issue of multiple select menus being displayed on the Quick Edit view.

= 1.2 =
* Updated plugin so that the current menu will be selected by default in the Quick Edit dropdown.
* Cleaned up the code a bit.

= 1.1 =
* Updated plugin so single and archive pages takes the default menu instead of listing everything.

= 1.0 =
* Initial plugin creation.
