=== Luminate For WP ===
Tags: luminate, ad network, affiliate, publisher, third party
Requires at least: 3.5
Tested up to: 3.9
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the Luminate Javascript code to your site.

== Description ==

This plugin automatically inserts the Luminate JavaScript code into the head or body of your site, per Luminate's specifications. Also includes code to force Luminate to work with CloudFlare.

Disclaimer: This plugin is not affiliated with or endorsed by Luminate.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, please go to <a href="http://wordpress.org/plugins/luminate-for-wp/#compatibility">Compatibility</a> and click "Broken" after verifying your WordPress version and the version of the plugin you are using.

Then, create a thread in the <a href="http://wordpress.org/support/plugin/luminate-for-wp">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/luminate-for-wp">Reviews</a> section to report issues or request new features.</strong>

== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; Luminate for WP, configure plugin.
4. View any of your pages, they should contain the Luminate code.

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; Luminate For WP and insert your Publisher URL. Make sure the "enabled" checkbox is checked. By default, code is inserted before the closing /head tag. That is all -- the code is automatically included in the head or body of every page.

= I entered my Publisher URL but don't see anything on the page. =

Are you caching your pages?

= I want the JavaScript code to appear at the bottom of the page, not the head. =

Go to Settings &raquo; Luminate For WP and look for the dropdown box next to "Code Location". Choose "body" instead of "head".

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_luminfwp_admin_css');`

== Screenshots ==

1. Plugin settings page
2. HTML source of a webpage

== Changelog ==

= 0.0.4 =
- updated .pot file and readme

= 0.0.3 =
- removed data-cfasync attribute, use my CloudFlare Rocket Loader Ignore plugin instead

= 0.0.2 =
- fixed validation issue

= 0.0.1 =
- created

== Upgrade Notice ==

= 0.0.4 =
- updated .pot file and readme

= 0.0.3 =
- removed data-cfasync attribute, use my CloudFlare Rocket Loader Ignore plugin instead

= 0.0.2 =
- fixed validation issue

= 0.0.1 =
- created