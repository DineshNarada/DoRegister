<?php
/**
 * Plugin Name: DoRegister - Registration System 
 * Description: A custom WordPress plugin that provides an advanced multi-step user registration system with login, profile view, and enhanced UX.
 * Version: 1.0.0
 * Author: Dinesh Narada
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
 
 if ( ! defined( 'ABSPATH' ) ) {
	 exit;
 }

 require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin.php';

// Initialize plugin
if ( class_exists( 'DoRegister\\Plugin' ) ) {
	\DoRegister\Plugin::init( __FILE__ );
}

