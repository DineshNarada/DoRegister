<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="doregister-login">
    <form id="doregister-login-form" method="post">
        <?php echo wp_nonce_field( 'doregister_login', 'doregister_login_nonce', true, false ); ?>
        <label>Username or Email<input type="text" name="user" required></label>
        <label>Password<input type="password" name="pass" required></label>
        <button type="submit">Login</button>
    </form>
    <div class="login-message"></div>
</div>
