<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user = wp_get_current_user();
?>
<div class="doregister-profile">
    <h2><?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h2>
    <p><strong>Email:</strong> <?php echo esc_html( $user->user_email ); ?></p>
    <p><strong>Phone:</strong> <?php echo esc_html( get_user_meta( $user->ID, 'phone', true ) ); ?></p>
    <p><strong>Country:</strong> <?php echo esc_html( get_user_meta( $user->ID, 'country', true ) ); ?></p>
    <p><strong>City:</strong> <?php echo esc_html( get_user_meta( $user->ID, 'city', true ) ); ?></p>
    <?php $photo_id = get_user_meta( $user->ID, 'profile_photo_id', true );
    if ( $photo_id ) : ?>
        <div class="profile-photo"><?php echo wp_get_attachment_image( $photo_id, 'thumbnail' ); ?></div>
    <?php endif; ?>
    <p><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a></p>
</div>
