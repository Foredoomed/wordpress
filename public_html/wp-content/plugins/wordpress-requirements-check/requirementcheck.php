<?php
/*
Plugin Name: WordPress 3.2 Requirements check
Plugin URI: http://fusionized.com/plugins/wordpress-requirements-check
Description: WordPress 3.2 will require PHP 5.2.4 and MySQL 5.0. This plugin preforms a simple check and lets you know if you're ready. 
Version: 1.0
Author: Ryan Duff / Fusionized Technology
Author URI: http://fusionized.com
License: GPL2
*/ 

/*  Copyright 2011  Ryan Duff  (email : ryan@fusionized.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function ftech_wp32_reqcheck() {
	global $wpdb;
	
	$php_version    = phpversion();
	$mysql_version  = $wpdb->db_version();
	$required_php_version = '5.2.4';
	$required_mysql_version = '5.0';
	$php_compat     = version_compare( $php_version, $required_php_version, '>=' );
	$mysql_compat   = version_compare( $mysql_version, $required_mysql_version, '>=' );
	
	if ( !$mysql_compat && !$php_compat )
		echo '<div id="message" class="error"><p>Your server <strong>does not</strong> meet the minimum requirements of PHP version ' . $required_php_version . ' or higher and MySQL version ' . $required_mysql_version . ' or higher needed for WordPress 3.2. You are running PHP version ' . $php_version . ' and MySQL version ' . $mysql_version . '</p></div>';
	elseif ( !$php_compat )
		echo '<div id="message" class="error"><p>Your server <strong>does not</strong> meet the minimum requirements of PHP version ' . $required_php_version . ' or higher needed for WordPress 3.2. You are running PHP version ' . $php_version . '.</p></div>';
	elseif ( !$mysql_compat )
		echo '<div id="message" class="error"><p>Your server <strong>does not</strong> meet the minimum requirements of MySQL version ' . $required_mysql_version . ' or higher needed for WordPress 3.2. You are running MySQL version ' . $mysql_version . '.</p></div>';
	elseif ( $mysql_compat || $php_compat )
		echo '<div id="message" class="updated"><p>Hooray! Your server meets the minimum requirements for WordPress 3.2! Now lets go eat some BBQ!</p><p>Feel free to deactivate this plugin to hide this notice.</p></div>';
}

add_action(admin_notices, ftech_wp32_reqcheck);
?>