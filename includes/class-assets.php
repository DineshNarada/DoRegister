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
        wp_enqueue_style( 'doregister-login-style', $url . 'assets/css/login.css', [ 'doregister-style' ], '1.0' );
        wp_enqueue_style( 'doregister-account-style', $url . 'assets/css/account.css', [ 'doregister-style' ], '1.0' );

        wp_enqueue_script( 'doregister-registration', $url . 'assets/js/registration.js', [ 'jquery' ], '1.0', true );
        wp_enqueue_script( 'doregister-login', $url . 'assets/js/login.js', [ 'jquery' ], '1.0', true );
        wp_localize_script( 'doregister-login', 'DrAjax', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'login_nonce'  => wp_create_nonce( 'doregister_login' ),
        ] );
        wp_enqueue_script( 'doregister-account', $url . 'assets/js/account.js', [ 'jquery' ], '1.0', true );

        // Load countries for JS
        $countries = include plugin_dir_path( $this->file ) . 'assets/countries.php';

        wp_localize_script( 'doregister-registration', 'DrAjax', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'doregister_register' ),
            'login_nonce'  => wp_create_nonce( 'doregister_login' ),
            'upload_nonce' => wp_create_nonce( 'doregister_upload' ),
            'countries'    => array_keys( $countries ),
        ] );

        wp_localize_script( 'doregister-account', 'doregister_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'doregister_update_profile' ),
            'change_password_nonce' => wp_create_nonce( 'doregister_change_password' ),
            'logout_redirect_url' => wp_logout_url( wp_login_url() ),
        ] );
    }
}
