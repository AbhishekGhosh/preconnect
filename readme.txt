=== Preconnect ===
Tags: preconnect, optimization
Requires at least: 4.0
Tested up to: 4.2
Contributors: Abhishek_Ghosh
Donate link: null
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds preconnect meta tags to your site.

== Description ==

This plugin implements Preconnect per the W3C specification. Hopefully, other browsers will eventually support DNS preconnect.

See https://www.w3.org/TR/resource-hints/

Disclaimer: This plugin is not affiliated with or endorsed by W3C.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, create a thread in the <a href="http://wordpress.org">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.


== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; Preconnect, configure plugin.
4. View any of your pages, they should contain the following meta tag:

`<meta http-equiv="x-dns-prefetch-control" content="on">`

In addition, when you will configure any additional domains (example - `cdn.example.com`), they should also be listed after the line of code above :

`<link rel="preconnect" href="https://cdn.example.com">`

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings &raquo; Preconnect and enter any domains you want to be preconnected, in addition to the ones already linked on your home page. Make sure the "enabled" checkbox is checked.

= I entered some text but don't see anything on the page. =

Are you caching your pages?

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_pc_admin_css');`

== Screenshots ==

1. Plugin settings page (note the URLs entered)
2. HTML source of a webpage (the URLs above are added to the HTML source)

== Changelog ==

= 0.1.0 =
- confirmed compatibility with WordPres 4.2
