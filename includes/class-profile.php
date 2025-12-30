<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Profile {
    public static function render_shortcode() {
        if ( ! is_user_logged_in() ) {
            return '<p>Please log in to view your profile.</p>';
        }

        $file = plugin_dir_path( Plugin::$file ) . 'templates/profile-view.php';
        ob_start();
        include $file;
        return ob_get_clean();
    }
}
