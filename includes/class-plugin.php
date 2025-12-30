<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Plugin {
    public static $file;

    public static function init( $file ) {
        self::$file = $file;
        self::includes();

        add_action( 'plugins_loaded', [ __CLASS__, 'setup' ] );
        register_activation_hook( $file, [ __CLASS__, 'activate' ] );
        register_deactivation_hook( $file, [ __CLASS__, 'deactivate' ] );
    }

    protected static function includes() {
        $dir = plugin_dir_path( self::$file );
        require_once $dir . 'includes/class-assets.php';
        require_once $dir . 'includes/class-ajax.php';
        require_once $dir . 'includes/class-registration.php';
        require_once $dir . 'includes/class-login.php';
        require_once $dir . 'includes/class-profile.php';
        require_once $dir . 'includes/admin-user-management.php';
    }

    public static function setup() {
        // Instantiate core pieces
        new Assets( self::$file );
        new Ajax();
        new AdminUserManagement();
        // Registration, Login, Profile register shortcodes
        add_shortcode( 'doregister_form', [ '\\DoRegister\\Registration', 'render_shortcode' ] );
        add_shortcode( 'doregister_login', [ '\\DoRegister\\Login', 'render_shortcode' ] );
        add_shortcode( 'doregister_profile', [ '\\DoRegister\\Profile', 'render_shortcode' ] );
    }

    public static function activate() {
        // Activation tasks (if any)
    }

    public static function deactivate() {
        // Deactivation tasks (if any)
    }
}
