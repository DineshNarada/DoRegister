<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ajax {
    public function __construct() {
        add_action( 'wp_ajax_nopriv_doregister_register', [ $this, 'handle_register' ] );
        add_action( 'wp_ajax_doregister_register', [ $this, 'handle_register' ] );

        add_action( 'wp_ajax_nopriv_doregister_login', [ $this, 'handle_login' ] );
        add_action( 'wp_ajax_doregister_login', [ $this, 'handle_login' ] );
        add_action( 'wp_ajax_nopriv_doregister_upload', [ $this, 'handle_upload' ] );
        add_action( 'wp_ajax_doregister_upload', [ $this, 'handle_upload' ] );

        // Profile update and account deletion (logged-in users only)
        add_action( 'wp_ajax_doregister_profile_update', [ $this, 'handle_profile_update' ] );
        add_action( 'wp_ajax_doregister_delete_account', [ $this, 'handle_delete_account' ] );
    }

    public function handle_register() {
        if ( empty( $_POST ) ) {
            wp_send_json_error( [ 'message' => 'No data.' ], 400 );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'doregister_register' ) ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
        }

        $data = isset( $_POST['data'] ) ? (array) $_POST['data'] : [];

        $result = Registration::create_user( $data );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ], 400 );
        }

        $opts = get_option( 'doregister_options', [] );
        $redirect = ! empty( $opts['redirect_url'] ) ? $opts['redirect_url'] : '';

        wp_send_json_success( [ 'message' => 'Registration successful', 'user_id' => $result, 'redirect' => $redirect ] );
    }

    public function handle_upload() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'doregister_upload' ) ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
        }

        if ( empty( $_FILES ) || ! isset( $_FILES['photo'] ) ) {
            wp_send_json_error( [ 'message' => 'No file uploaded.' ], 400 );
        }

        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }
        if ( ! function_exists( 'wp_insert_attachment' ) ) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
        }

        $file = $_FILES['photo'];

        // Basic validation: size limit 2MB and allowed image types
        $max = 2 * 1024 * 1024;
        if ( $file['size'] > $max ) {
            wp_send_json_error( [ 'message' => 'File too large (max 2MB).' ], 400 );
        }

        $allowed = [ 'jpg', 'jpeg', 'png', 'gif' ];
        $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        if ( ! in_array( $ext, $allowed, true ) ) {
            wp_send_json_error( [ 'message' => 'Invalid file type.' ], 400 );
        }

        $overrides = [ 'test_form' => false ];
        $movefile = wp_handle_upload( $file, $overrides );

        if ( isset( $movefile['error'] ) ) {
            wp_send_json_error( [ 'message' => $movefile['error'] ], 500 );
        }

        $filename = $movefile['file'];
        $filetype = wp_check_filetype( basename( $filename ), null );

        $attachment = [
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_file_name( basename( $filename ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $attach_id = wp_insert_attachment( $attachment, $movefile['file'] );
        if ( is_wp_error( $attach_id ) ) {
            wp_send_json_error( [ 'message' => $attach_id->get_error_message() ], 500 );
        }

        $metadata = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
        wp_update_attachment_metadata( $attach_id, $metadata );

        $url = wp_get_attachment_url( $attach_id );

        wp_send_json_success( [ 'attachment_id' => $attach_id, 'url' => $url ] );
    }

    public function handle_login() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'doregister_login' ) ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
        }

        $creds = [
            'user_login'    => isset( $_POST['user'] ) ? sanitize_text_field( wp_unslash( $_POST['user'] ) ) : '',
            'user_password' => isset( $_POST['pass'] ) ? $_POST['pass'] : '',
            'remember'      => false,
        ];

        $user = wp_signon( $creds, is_ssl() );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( [ 'message' => $user->get_error_message() ], 400 );
        }

        wp_send_json_success( [ 'message' => 'Login successful', 'redirect' => home_url() ] );
    }

    public function handle_profile_update() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( [ 'message' => 'Not authenticated.' ], 403 );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'doregister_profile' ) ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
        }

        $user_id = get_current_user_id();

        $data = isset( $_POST['data'] ) ? (array) $_POST['data'] : [];

        // Email update - ensure valid and not in use by others
        if ( isset( $data['email'] ) ) {
            $email = sanitize_email( wp_unslash( $data['email'] ) );
            if ( ! is_email( $email ) ) {
                wp_send_json_error( [ 'message' => 'Invalid email address.' ], 400 );
            }
            $owner = email_exists( $email );
            if ( $owner && (int) $owner !== $user_id ) {
                wp_send_json_error( [ 'message' => 'Email is already in use.' ], 400 );
            }
        }

        $update = [ 'ID' => $user_id ];
        if ( isset( $data['full_name'] ) ) {
            $update['display_name'] = sanitize_text_field( wp_unslash( $data['full_name'] ) );
            $update['first_name'] = sanitize_text_field( wp_unslash( $data['full_name'] ) );
        }
        if ( isset( $data['email'] ) ) {
            $update['user_email'] = $email;
        }
        if ( isset( $data['password'] ) && $data['password'] !== '' ) {
            if ( strlen( $data['password'] ) < 6 ) {
                wp_send_json_error( [ 'message' => 'Password must be at least 6 characters.' ], 400 );
            }
            $update['user_pass'] = $data['password'];
        }

        $result = wp_update_user( $update );
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ], 500 );
        }

        // Update meta fields
        $meta_fields = [ 'phone', 'country', 'city', 'gender', 'dob', 'interests', 'profile_photo_id' ];
        foreach ( $meta_fields as $m ) {
            if ( isset( $data[ $m ] ) ) {
                $value = $data[ $m ];
                if ( 'interests' === $m && is_string( $value ) ) {
                    $value = array_map( 'sanitize_text_field', array_map( 'trim', explode( ',', $value ) ) );
                } else {
                    $value = sanitize_text_field( wp_unslash( $value ) );
                }
                update_user_meta( $user_id, $m, $value );
            }
        }

        do_action( 'doregister_user_updated', $user_id );

        wp_send_json_success( [ 'message' => 'Profile updated.' ] );
    }

    public function handle_delete_account() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( [ 'message' => 'Not authenticated.' ], 403 );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'doregister_delete' ) ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce.' ], 403 );
        }

        $user_id = get_current_user_id();
        $password = isset( $_POST['password'] ) ? $_POST['password'] : '';

        // Prevent administrators or installer account deletion via frontend
        $installer = (int) get_option( 'doregister_installer_id', 0 );
        if ( user_can( $user_id, 'manage_options' ) || ( $installer && $user_id === $installer ) ) {
            wp_send_json_error( [ 'message' => 'Account deletion is not allowed for this user via frontend.' ], 403 );
        }

        if ( empty( $password ) ) {
            wp_send_json_error( [ 'message' => 'Please confirm your password.' ], 400 );
        }

        $user = get_userdata( $user_id );
        if ( ! wp_check_password( $password, $user->user_pass, $user_id ) ) {
            wp_send_json_error( [ 'message' => 'Password incorrect.' ], 403 );
        }

        // Delete user and reassign content to 0 (none)
        require_once ABSPATH . 'wp-admin/includes/user.php';
        $deleted = wp_delete_user( $user_id );

        if ( ! $deleted ) {
            wp_send_json_error( [ 'message' => 'Could not delete account.' ], 500 );
        }

        do_action( 'doregister_user_deleted', $user_id );

        wp_send_json_success( [ 'message' => 'Account deleted.' ] );
    }
}
