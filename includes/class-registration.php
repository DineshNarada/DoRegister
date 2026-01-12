<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Registration {
    public static function render_shortcode() {
        $file = plugin_dir_path( Plugin::$file ) . 'templates/registration-form.php';
        $countries = include plugin_dir_path( Plugin::$file ) . 'assets/countries.php';
        ob_start();
        include $file;
        return ob_get_clean();
    }

    public static function create_user( $data ) {
        $full_name = isset( $data['full_name'] ) ? sanitize_text_field( $data['full_name'] ) : '';
        $email     = isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '';
        $password  = isset( $data['password'] ) ? $data['password'] : '';
        $phone     = isset( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '';
        $country   = isset( $data['country'] ) ? sanitize_text_field( $data['country'] ) : '';
        $city      = isset( $data['city'] ) ? sanitize_text_field( $data['city'] ) : '';
        $gender    = isset( $data['gender'] ) ? sanitize_text_field( $data['gender'] ) : '';
        $dob       = isset( $data['dob'] ) ? sanitize_text_field( $data['dob'] ) : '';
        $interests = isset( $data['interests'] ) ? array_map( 'sanitize_text_field', (array) $data['interests'] ) : [];
        $photo_id  = isset( $data['photo_id'] ) ? intval( $data['photo_id'] ) : 0;

        if ( empty( $full_name ) || empty( $email ) || empty( $password ) || empty( $phone ) ) {
            return new \WP_Error( 'missing_fields', 'Please fill required fields.' );
        }

        if ( ! is_email( $email ) ) {
            return new \WP_Error( 'invalid_email', 'Invalid email address.' );
        }

        if ( email_exists( $email ) ) {
            return new \WP_Error( 'email_exists', 'Email is already registered.' );
        }

        if ( strlen( $password ) < 6 ) {
            return new \WP_Error( 'password_weak', 'Password must be at least 6 characters.' );
        }

        // Create a username from email prefix and ensure uniqueness
        $prefix = preg_replace( '/[^a-z0-9_\-]/i', '', strstr( $email, '@', true ) );
        $username = $prefix ?: 'user' . time();
        $attempt = $username;
        $i = 1;
        while ( username_exists( $attempt ) ) {
            $attempt = $username . $i;
            $i++;
        }

        $userdata = [
            'user_login' => $attempt,
            'user_email' => $email,
            'user_pass'  => $password,
            'first_name' => $full_name,
            'role'       => 'subscriber',
        ];

        $user_id = wp_insert_user( $userdata );

        if ( is_wp_error( $user_id ) ) {
            return $user_id;
        }

        update_user_meta( $user_id, 'phone', $phone );
        update_user_meta( $user_id, 'country', $country );
        update_user_meta( $user_id, 'city', $city );
        update_user_meta( $user_id, 'gender', $gender );
        update_user_meta( $user_id, 'dob', $dob );
        update_user_meta( $user_id, 'interests', $interests );

        if ( $photo_id ) {
            update_user_meta( $user_id, 'profile_photo_id', $photo_id );
        }

        /**
         * Action fired after a successful DoRegister user registration
         * @param int $user_id
         */
        do_action( 'doregister_user_registered', $user_id );

        return $user_id;
    }
}
