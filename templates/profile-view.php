<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user = wp_get_current_user();
$phone = get_user_meta( $user->ID, 'phone', true );
$country = get_user_meta( $user->ID, 'country', true );
$city = get_user_meta( $user->ID, 'city', true );
$gender = get_user_meta( $user->ID, 'gender', true );
$dob = get_user_meta( $user->ID, 'dob', true );
$interests = get_user_meta( $user->ID, 'interests', true );
$photo_id = get_user_meta( $user->ID, 'profile_photo_id', true );
?>
<div class="doregister-profile">
    <h2><?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h2>

    <form id="doregister-profile-form" class="doregister-form" method="post" enctype="multipart/form-data">
        <p>
            <label for="dr-full-name"><strong>Name</strong></label><br />
            <input type="text" id="dr-full-name" name="full_name" value="<?php echo esc_attr( $user->display_name ?: $user->user_login ); ?>" class="regular-text" />
        </p>
        <p>
            <label for="dr-email"><strong>Email</strong></label><br />
            <input type="email" id="dr-email" name="email" value="<?php echo esc_attr( $user->user_email ); ?>" class="regular-text" />
        </p>
        <p>
            <label for="dr-password"><strong>New Password</strong> <small>(leave blank to keep current)</small></label><br />
            <input type="password" id="dr-password" name="password" class="regular-text" />
        </p>
        <p>
            <label for="dr-phone"><strong>Phone</strong></label><br />
            <input type="text" id="dr-phone" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" />
        </p>
        <p>
            <label for="dr-country"><strong>Country</strong></label><br />
            <input type="text" id="dr-country" name="country" value="<?php echo esc_attr( $country ); ?>" class="regular-text" />
        </p>
        <p>
            <label for="dr-city"><strong>City</strong></label><br />
            <input type="text" id="dr-city" name="city" value="<?php echo esc_attr( $city ); ?>" class="regular-text" />
        </p>
        <p>
            <label for="dr-gender"><strong>Gender</strong></label><br />
            <input type="text" id="dr-gender" name="gender" value="<?php echo esc_attr( $gender ); ?>" class="regular-text" />
        </p>
        <p>
            <label for="dr-dob"><strong>Date of Birth</strong></label><br />
            <input type="date" id="dr-dob" name="dob" value="<?php echo esc_attr( $dob ); ?>" />
        </p>
        <p>
            <label for="dr-interests"><strong>Interests</strong></label><br />
            <textarea id="dr-interests" name="interests" rows="3" class="regular-text"><?php echo esc_textarea( is_array( $interests ) ? implode( ', ', $interests ) : $interests ); ?></textarea>
        </p>

        <p>
            <label><strong>Profile Photo</strong></label><br />
            <div id="dr-photo-preview">
                <?php if ( $photo_id ) { echo wp_get_attachment_image( $photo_id, 'thumbnail' ); } else { echo 'No photo'; } ?>
            </div>
            <input type="file" id="dr-photo" name="photo" accept="image/*" />
            <input type="hidden" id="dr-photo-id" name="profile_photo_id" value="<?php echo esc_attr( $photo_id ); ?>" />
        </p>

        <p>
            <button type="button" id="doregister-save-profile" class="button button-primary">Save Profile</button>
            <span id="dr-profile-message" class="doregister-message"></span>
        </p>
    </form>

    <hr />

    <div class="doregister-delete">
        <h3>Delete Account</h3>
        <p>Deleting your account is permanent and will remove your profile and data.</p>
        <p>
            <label for="dr-delete-password">Confirm Password</label><br />
            <input type="password" id="dr-delete-password" name="password" class="regular-text" />
        </p>
        <p>
            <button type="button" id="doregister-delete-account" class="button button-danger">Delete My Account</button>
            <span id="dr-delete-message" class="doregister-message"></span>
        </p>
    </div>

    <p><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a></p>
</div>
