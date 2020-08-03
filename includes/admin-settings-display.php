<?php

/**
 *
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/DavePodosyan/nolt-identify-users
 * @since      1.0.0
 *
 * @package    Nolt Identify Users
 */

?>

<div class="wrap">
  <div id="icon-themes" class="icon32"></div>
	<h2><?php echo esc_html('Nolt Identify Users - Settings Page', 'nolt-identify-users') ?></h2>
	<form method="POST" action="options.php">
	   <?php
		   settings_fields( 'nolt_identify_users_general_settings' );
		   do_settings_sections( 'nolt_identify_users_general_settings' );
		 ?>
		 <?php submit_button(); ?>
	</form>
</div>
