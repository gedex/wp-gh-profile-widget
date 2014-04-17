<?php
/**
 * Plugin Name: GitHub Profile Widget
 * Plugin URI: https://github.com/gedex/wp-github-api
 * Description: Plugin extension for GitHub API plugin for showing off GitHub profile in a widget.
 * Version: 0.1.0
 * Author: Akeda Bagus
 * Author URI: http://gedex.web.id
 * Text Domain: github-api
 * Domain Path: /languages
 * License: GPL v2 or later
 * Requires at least: 3.6
 * Tested up to: 3.8
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

add_action( 'github_api_init', function( $api ) {
	require_once __DIR__ . '/includes/plugin.php';

	$GLOBALS['gh_profile_widget'] = new GH_Profile_Widget_Plugin();
	$GLOBALS['gh_profile_widget']->run( __FILE__, $api );
} );
