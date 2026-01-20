<?php
/**
 * Plugin Name: Lasoon - Maintenance Plugin
 * Plugin URI:
 * Description: Provides a Various Layouts for Maintenance.
 * Version: 2.0.0
 * Author: The_Krishna
 * Author URI: https://themeforest.net/user/the_krishna
 * Text Domain: lasoon 
 *
 * @package Lasoon
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! defined( 'LASOON_PLUGIN_FILE' ) ) {
	define( 'LASOON_PLUGIN_FILE', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'LASOON_PLUGIN_PATH' ) ) {
    define( 'LASOON_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if( !defined('LASOON_IMG_PATH')){
    define( 'LASOON_IMG_PATH',  plugin_dir_url( __FILE__ ).'public/assets/images' );
}
if( !defined('LASOON_PUBLIC_PATH')){
    define( 'LASOON_PUBLIC_PATH',  plugin_dir_url( __FILE__ ).'public/' );
}
if( !defined('LASOON_PLUGIN_SLUG')){
    define( 'LASOON_PLUGIN_SLUG',  'lasoon-maintenance' );
}
/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LASOON_VERSION', '2.0.0' );

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
require_once(ABSPATH."/wp-includes/pluggable.php");
require_once('admin/lasoon-admin.php');
require_once('public/class-lasoon-public.php');