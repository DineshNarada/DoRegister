<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AdminUserManagement {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'show_user_profile', [ $this, 'show_extra_fields' ] );
        add_action( 'edit_user_profile', [ $this, 'show_extra_fields' ] );
        add_action( 'personal_options_update', [ $this, 'save_extra_fields' ] );
        add_action( 'edit_user_profile_update', [ $this, 'save_extra_fields' ] );
    }

    public function add_menu() {
        add_users_page(
            'DoRegister User Management',
            'DoRegister Users',
            'manage_options',
            'doregister-users',
            [ $this, 'render_page' ]
        );
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1>DoRegister User Management</h1>
            <p>Manage users registered via DoRegister plugin.</p>
            <?php
            $users = get_users( [
                'meta_key' => 'phone', // assuming all DoRegister users have phone meta
                'meta_compare' => 'EXISTS'
            ] );

            if ( $users ) {
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr><th>User</th><th>Email</th><th>Phone</th><th>Country</th><th>City</th><th>Profile Photo</th></tr></thead><tbody>';
                foreach ( $users as $user ) {
                    $phone = get_user_meta( $user->ID, 'phone', true );
                    $country = get_user_meta( $user->ID, 'country', true );
                    $city = get_user_meta( $user->ID, 'city', true );
                    $photo_id = get_user_meta( $user->ID, 'profile_photo_id', true );
                    $photo_url = $photo_id ? wp_get_attachment_image_url( $photo_id, 'thumbnail' ) : '';
                    echo '<tr>';
                    echo '<td>' . esc_html( $user->display_name ) . '</td>';
                    echo '<td>' . esc_html( $user->user_email ) . '</td>';
                    echo '<td>' . esc_html( $phone ) . '</td>';
                    echo '<td>' . esc_html( $country ) . '</td>';
                    echo '<td>' . esc_html( $city ) . '</td>';
                    echo '<td>' . ( $photo_url ? '<img src="' . esc_url( $photo_url ) . '" width="50" height="50" />' : 'No photo' ) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p>No DoRegister users found.</p>';
            }
            ?>
        </div>
        <?php
    }

    public function show_extra_fields( $user ) {
        $phone = get_user_meta( $user->ID, 'phone', true );
        $country = get_user_meta( $user->ID, 'country', true );
        $city = get_user_meta( $user->ID, 'city', true );
        $gender = get_user_meta( $user->ID, 'gender', true );
        $dob = get_user_meta( $user->ID, 'dob', true );
        $interests = get_user_meta( $user->ID, 'interests', true );
        $photo_id = get_user_meta( $user->ID, 'profile_photo_id', true );
        $photo_url = $photo_id ? wp_get_attachment_image_url( $photo_id, 'thumbnail' ) : '';
        ?>
        <h3>DoRegister Extra Fields</h3>
        <table class="form-table">
            <tr>
                <th><label for="phone">Phone</label></th>
                <td><input type="text" name="phone" id="phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="country">Country</label></th>
                <td><input type="text" name="country" id="country" value="<?php echo esc_attr( $country ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="city">City</label></th>
                <td><input type="text" name="city" id="city" value="<?php echo esc_attr( $city ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="gender">Gender</label></th>
                <td><input type="text" name="gender" id="gender" value="<?php echo esc_attr( $gender ); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="dob">Date of Birth</label></th>
                <td><input type="date" name="dob" id="dob" value="<?php echo esc_attr( $dob ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="interests">Interests</label></th>
                <td><textarea name="interests" id="interests" rows="3" class="regular-text"><?php echo esc_textarea( is_array( $interests ) ? implode( ', ', $interests ) : $interests ); ?></textarea></td>
            </tr>
            <tr>
                <th>Profile Photo</th>
                <td><?php if ( $photo_url ) { echo '<img src="' . esc_url( $photo_url ) . '" width="100" height="100" />'; } else { echo 'No photo'; } ?></td>
            </tr>
        </table>
        <?php
    }

    public function save_extra_fields( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }

        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST['phone'] ?? '' ) );
        update_user_meta( $user_id, 'country', sanitize_text_field( $_POST['country'] ?? '' ) );
        update_user_meta( $user_id, 'city', sanitize_text_field( $_POST['city'] ?? '' ) );
        update_user_meta( $user_id, 'gender', sanitize_text_field( $_POST['gender'] ?? '' ) );
        update_user_meta( $user_id, 'dob', sanitize_text_field( $_POST['dob'] ?? '' ) );
        $interests = sanitize_text_field( $_POST['interests'] ?? '' );
        update_user_meta( $user_id, 'interests', $interests ? explode( ',', $interests ) : [] );
    }
}
