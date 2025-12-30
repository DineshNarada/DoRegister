<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Login {
    public static function render_shortcode() {
        $file = plugin_dir_path( Plugin::$file ) . 'templates/login-form.php';
        ob_start();
        include $file;
        return ob_get_clean();
    }
}
