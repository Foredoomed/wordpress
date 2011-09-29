=== Attachment Manager ===
Contributors: aaroncampbell
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=11106634
Tags: posts, files, attachments
Requires at least: 2.8
Tested up to: 3.0.0
Stable tag: 2.1.1

This will allow you to better manage how your attachments are handled.  Attachments can be easily listed after posts, complete with icons. Requires PHP 5+

== Description ==

This plugin allows you to list attached files on posts.  It comes with a default
icon set (by famfamfam), with icons for most major files types.  You can add
your own icons, as well as control many aspects of the file list (whether you
want to show icons, whether to add the lists to exerpts, place them on all posts,
show them on category pages, etc).

Requires PHP 5+

== Installation ==

1. Upload the whole `attachment-manager` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You can also optionally chmod the `/wp-content/plugins/attachment-manager/icons` directory to allow you to add/remove icons via the options page

== Frequently Asked Questions ==

= Can I list only certain attachments? =

You have two options; list all attachments or list attachments that are not already linked in the post.

= Can I add new icons? =

Absolutely, you can manually upload them to wp-content/plugins/attachment-manager/icons or if you chmod that folder, you can upload them via the plugin's options page.

== Changelog ==
= 2.1.1 =
* Add meta box for checkbox when you're only showing attachments on some posts
* Add the checkbox to show attachments to pages as well as posts

= 2.1.0 =
* Requires PHP 5+
* Requires WordPress 2.8+
* Updated to use the <a href="http://xavisys.com/xavisys-wordpress-plugin-framework/">Xavisys WordPress Plugin Framework</a>
* Added more options for where to show attachments.

= 2.0.2 =
* Can now recognize thumbnails and medium size images in posts and leave them off the list.
* No longer uses the deprecated "get_settings"
* Moved this change log to the readme.txt file to work with the new feature on WordPress.org

= 2.0.1 =
* Fixed issue with file types on upgrade to 2.0.x

= 2.0.0 =
* Completely updated to work with 2.6

= 1.0.1 =
* Fixed problem with plugin causing custom excerpts to not be shown

= 1.0.0 =
* Added to the wordpress.org repository

= 0.1.3 =
* Added support for WP 2.2.x-s

= 0.1.2 =
* Added the option to not show file lists on excerpts

= 0.1.1 =
* Added the option to not show file lists on category pages

= 0.1 =
* Original Version
