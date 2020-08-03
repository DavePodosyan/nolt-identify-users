<?php
/*
 * Plugin Name: Nolt Identify Users
 * Plugin URI: https://github.com/DavePodosyan/nolt-identify-users
 * Description: Allow website users login to Nolt Board using Single Sign-On mechanism.
 * Version: 1
 * Author: David Podosyan
 * Text Domain: nolt-identify-users
 * Author URI: https://github.com/DavePodosyan
 * License: License GNU General Public License version 2 or later;
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if(!defined('NOLT_IDENTIFY_USERS_VERSION'))
	define('NOLT_IDENTIFY_USERS_VERSION','1.1.25');

if(!defined('NOLT_IDENTIFY_USERS_URL'))
	define('NOLT_IDENTIFY_USERS_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));

if(!defined('NOLT_IDENTIFY_USERS_DIR'))
	define('NOLT_IDENTIFY_USERS_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));

if(!class_exists('Nolt_Auth')){
	require_once NOLT_IDENTIFY_USERS_DIR.'/includes/class-nolt-auth.php';
}
