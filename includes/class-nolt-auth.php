<?php
use \Firebase\JWT\JWT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Nolt_Auth{

	private static $_instance = null;

	public function init(){
		$this->_includes();
		if(!get_option('nolt_identify_users_secret_key', 0) == 0){
			add_action( 'wp_enqueue_scripts', array(__CLASS__,'enqueue_scripts' ));
			add_filter( 'script_loader_tag', array(__CLASS__,'async_load'), 10, 2 );
		}
		if(!get_option('nolt_identify_users_nolt_address', 0) == 0){
			add_filter('nav_menu_link_attributes', array(__CLASS__,'modify_menu_link_data_attr'), 50, 3);
		}

	}


	private function _includes(){
		//JWT
		include_once NOLT_IDENTIFY_USERS_DIR.'/includes/class-JWT.php';

		//Admin Menu
		include_once NOLT_IDENTIFY_USERS_DIR.'/includes/class-nolth-admin.php';
	}


	public static function generateNoltToken(){

		$user = wp_get_current_user();
		$payload = [
			// The ID that you use in your app for this user
			'id' => $user->ID,
			// The user's email address that
			// Nolt should use for notifications
			'email' => $user->user_email,
			// The display name for this user
			'name' => $user->first_name, //get_user_meta($user->ID,'nickname',true),
			// Optional: The URL to the user's avatar picture
			'imageUrl' => get_avatar_url($user->ID, array('size' => 256))
		];

		return JWT::encode($payload, get_option('nolt_identify_users_secret_key', 0), 'HS256');
	}

	public static function enqueue_scripts(){

		if(is_user_logged_in()){
			wp_enqueue_script( 'nolt-widgets', 'https://cdn.nolt.io/widgets.js', array(), false, true);
			wp_add_inline_script( 'nolt-widgets', 'window.noltQueue=window.noltQueue||[];function nolt(){noltQueue.push(arguments)}' );
			wp_add_inline_script( 'nolt-widgets', "nolt('identify', { jwt: '". self::generateNoltToken() ."'});" );
		}

	}

	public static function  async_load( $tag, $handle ) {

		if ( 'nolt-widgets' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' async src', $tag ); // async the script

	}

	public static function modify_menu_link_data_attr( $atts, $item, $args ) {
		$url = parse_url($item->url);
		$option_url = parse_url(get_option('nolt_identify_users_nolt_address'));
		if ($url['host'] == $option_url['host'] ) {
			$atts['data-nolt'] = 'true';
		}

		return $atts;
	}
	/**
	 *
	 * @return Nolt_Auth
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
Nolt_Auth::get_instance()->init();
