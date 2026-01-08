<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user = wp_get_current_user();
$photo_id = get_user_meta( $user->ID, 'profile_photo_id', true );
$phone = get_user_meta( $user->ID, 'phone', true );
$country = get_user_meta( $user->ID, 'country', true );
$city = get_user_meta( $user->ID, 'city', true );
$initials = strtoupper( substr( $user->display_name ?: $user->user_login, 0, 2 ) );
?>
<div class="doregister-profile">
    <div class="profile-header">
        <div class="profile-photo-container">
            <?php if ( $photo_id ) : ?>
                <div class="profile-photo" data-photo-id="<?php echo esc_attr( $photo_id ); ?>">
                    <?php echo wp_get_attachment_image( $photo_id, 'medium' ); ?>
                </div>
            <?php else : ?>
                <div class="profile-photo profile-photo-placeholder">
                    <span class="profile-initials"><?php echo esc_html( $initials ); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h2 class="profile-username"><?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h2>
            <div class="profile-actions">
                <button class="btn btn-secondary edit-profile-btn">Edit Profile</button>
                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="btn btn-danger logout-btn">Logout</a>
            </div>
        </div>
    </div>

    <div class="profile-details">
        <div class="profile-field">
            <span class="field-icon">📧</span>
            <span class="field-label">Email:</span>
            <span class="field-value"><?php echo esc_html( $user->user_email ); ?></span>
        </div>
        <?php if ( ! empty( $phone ) ) : ?>
            <div class="profile-field">
                <span class="field-icon">📞</span>
                <span class="field-label">Phone:</span>
                <span class="field-value"><?php echo esc_html( $phone ); ?></span>
            </div>
        <?php endif; ?>
        <?php if ( ! empty( $country ) ) : ?>
            <div class="profile-field">
                <span class="field-icon">🌍</span>
                <span class="field-label">Country:</span>
                <span class="field-value"><?php echo esc_html( $country ); ?></span>
            </div>
        <?php endif; ?>
        <?php if ( ! empty( $city ) ) : ?>
            <div class="profile-field">
                <span class="field-icon">📍</span>
                <span class="field-label">City:</span>
                <span class="field-value"><?php echo esc_html( $city ); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="profile-security">
        <h3>Security</h3>
        <div class="change-password-section">
            <button class="btn btn-secondary change-password-btn">Change Password</button>
            <div class="change-password-form" style="display: none;">
                <form id="change-password-form">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                    <button type="button" class="btn btn-secondary cancel-password-btn">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Mode Overlay -->
    <div class="edit-mode-overlay" style="display: none;">
        <div class="edit-form">
            <h3>Edit Profile</h3>
            <form id="edit-profile-form">
                <div class="form-group">
                    <label for="edit_phone">Phone</label>
                    <input type="text" id="edit_phone" name="phone" value="<?php echo esc_attr( $phone ); ?>">
                </div>
                <div class="form-group">
                    <label for="edit_country">Country</label>
                    <input type="text" id="edit_country" name="country" value="<?php echo esc_attr( $country ); ?>">
                </div>
                <div class="form-group">
                    <label for="edit_city">City</label>
                    <input type="text" id="edit_city" name="city" value="<?php echo esc_attr( $city ); ?>">
                </div>
                <div class="form-group">
                    <label for="edit_photo">Profile Photo</label>
                    <input type="file" id="edit_photo" name="photo" accept="image/*">
                    <div class="file-upload-wrapper">
                        <button type="button" class="btn btn-secondary file-upload-btn">Upload</button>
                        <span class="file-name">No image chosen</span>
                    </div>
                    <div class="image-preview" id="image-preview" style="display: none;">
                        <img id="preview-img" src="" alt="Preview">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary cancel-edit-btn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Photo Preview Modal -->
    <div class="photo-modal" style="display: none;">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <img id="modal-image" src="" alt="Profile Photo">
        </div>
    </div>

    <div id="message" style="display: none;"></div>
</div>
