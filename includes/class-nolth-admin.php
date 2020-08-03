<?php

class Nolth_Admin{
	public static function init(){
		if(!is_admin()){

		}
    add_action('admin_menu', array(__CLASS__, 'admin_menu'));
    add_action('admin_init', array(__CLASS__, 'registerAndBuildFields' ));

	}

	public static function admin_menu(){
    //add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    add_submenu_page( 'options-general.php', 'Nolt Idenitfy Users Settings', 'Nolt Idenitfy Users', 'manage_options', 'nolt-identify-users-settings', array( __CLASS__, 'displayPluginAdminSettings' ));
	}

	public static function displayPluginAdminSettings(){
    // set this var to be used in the settings-display view
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
    if(isset($_GET['error_message'])){
      add_action('admin_notices', array(__CLASS__,'pluginNameSettingsMessages'));
      do_action( 'admin_notices', $_GET['error_message'] );
    }
    require_once NOLT_IDENTIFY_USERS_DIR.'/includes/admin-settings-display.php';
	}

	public static function pluginNameSettingsMessages($error_message){
    switch ($error_message) {
      case '1':
        $message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'my-text-domain' );
        $err_code = esc_attr( 'nolt_identify_users_secret_key' );
        $setting_field = 'nolt_identify_users_secret_key';
        break;
    }
      $type = 'error';
      add_settings_error( $setting_field, $err_code, $message, $type );
  }
  public static function registerAndBuildFields(){
    add_settings_section(
      // ID used to identify this section and with which to register options
      'nolt_identify_users_general_section',
      // Title to be displayed on the administration page
      '',
      // Callback used to render the description of the section
       array( __CLASS__, 'display_general_account' ),
      // Page on which to add this section of options
      'nolt_identify_users_general_settings'
    );
    unset($args);
    $args = array (
              'type'      => 'input',
              'subtype'   => 'text',
              'id'    => 'nolt_identify_users_secret_key',
              'name'      => 'nolt_identify_users_secret_key',
              'required' => 'true',
              'get_options_list' => '',
              'value_type'=>'normal',
              'wp_data' => 'option'
            );
    add_settings_field(
      'nolt_identify_users_secret_key',
      'Nolt Secret Key',
      array( __CLASS__, 'render_settings_field' ),
      'nolt_identify_users_general_settings',
      'nolt_identify_users_general_section',
      $args
    );
    unset($args);
    $args = array (
              'type'      => 'input',
              'subtype'   => 'text',
              'id'    => 'nolt_identify_users_nolt_address',
              'name'      => 'nolt_identify_users_nolt_address',
              'required' => 'true',
              'get_options_list' => '',
              'value_type'=>'normal',
              'wp_data' => 'option'
            );
    add_settings_field(
      'nolt_identify_users_nolt_address',
      'Nolt Dashboard Link',
      array( __CLASS__, 'render_settings_field' ),
      'nolt_identify_users_general_settings',
      'nolt_identify_users_general_section',
      $args
    );


    register_setting(
            'nolt_identify_users_general_settings',
            'nolt_identify_users_secret_key'
            );
    register_setting(
            'nolt_identify_users_general_settings',
            'nolt_identify_users_nolt_address'
            );
  }

  public static function display_general_account() {
	  echo '<p>Plugin will not load scripts until <b>Nolt Secret Key</b> provided</p>';
	}

  public static function render_settings_field($args) {
      if($args['wp_data'] == 'option') {
        $wp_data_value = get_option($args['name']);
      } elseif($args['wp_data'] == 'post_meta'){
        $wp_data_value = get_post_meta($args['post_id'], $args['name'], true );
      }

      switch ($args['type']) {
        case 'input':
            $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
            if($args['subtype'] != 'checkbox'){
                $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
                $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
                $step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
                $min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
                $max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
                if(isset($args['disabled'])){
                    echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'_disabled" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.$args['id'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
                } else {
                    echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'" "'.$args['required'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
                }

            } else {
                $checked = ($value) ? 'checked' : '';
                echo '<input type="'.$args['subtype'].'" id="'.$args['id'].'" "'.$args['required'].'" name="'.$args['name'].'" size="40" value="1" '.$checked.' />';
            }
            break;
        default:
          break;
    }
  }
}

Nolth_Admin::init();
