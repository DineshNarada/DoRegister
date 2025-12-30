<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Assets {
    protected $file;

    public function __construct( $file ) {
        $this->file = $file;
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    public function enqueue() {
        $url = plugin_dir_url( $this->file );

        wp_enqueue_style( 'doregister-style', $url . 'assets/css/style.css', [], '1.0' );

        wp_enqueue_script( 'doregister-registration', $url . 'assets/js/registration.js', [ 'jquery' ], '1.0', true );
        wp_enqueue_script( 'doregister-login', $url . 'assets/js/login.js', [ 'jquery' ], '1.0', true );

        wp_localize_script( 'doregister-registration', 'DrAjax', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'doregister_register' ),
            'login_nonce'  => wp_create_nonce( 'doregister_login' ),
            'upload_nonce' => wp_create_nonce( 'doregister_upload' ),
        ] );
    }
}
