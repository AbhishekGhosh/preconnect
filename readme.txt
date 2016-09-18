=== Preconnect ===
Contributors: Abhishek_Ghosh
Author: Abhishek_Ghosh
URL: https://thecustomizewindows.com/2016/04/domain-preconnect-wordpress-plugin-rel-preconnect/
Tags: preconnect, optimization
Requires at least: 4.0
Tested up to: 4.6.1
Donate link: https://thecustomizewindows.com/
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds preconnect crossorigin link relation type to indicate an origin that will be used to fetch required resources as described in W3C Working Draft.

== Description ==

This plugin implements Preconnect per the W3C Working Draft, dated 27th May 2016. Hopefully, all the other browsers will eventually support DNS preconnect. See https://www.w3.org/TR/resource-hints/

Disclaimer: This plugin is not affiliated with or endorsed by W3C.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just does not work, create a thread in the <a href="http://wordpress.org">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues.


== Installation ==

1. Upload plugin file through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings » Preconnect, configure plugin.
4. View any of your pages, they should contain the following meta tag:

When you will configure any additional domains (example - `cdn.example.com`), they should also be listed after the line of code above :

`<link rel="preconnect" href="https://example.com" crossorigin>`

== Frequently Asked Questions ==

= How do I use the plugin? =

Go to Settings » Preconnect and enter any domains you want to be preconnected, in addition to the ones already linked on your home page. Make sure the "enabled" checkbox is checked.

= I entered some text but don't see anything on the page. =

Are you caching your pages?

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_pc_admin_css');`

== Screenshots ==

1. Plugin settings page (note the URLs entered)
2. HTML source of a webpage (the URLs above are added to the HTML source)

== Upgrade Notice ==

= 1.0 =
WordPress >= 3.9 required. Domain expected to be HSTS.

== Changelog ==

= 1.0 =
- confirmed compatibility with WordPres 4.6.1

= 0.1.0 =
- confirmed compatibility with WordPres 4.2
