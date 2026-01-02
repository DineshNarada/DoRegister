<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="doregister-wrap">
    <form id="doregister-form" class="doregister-form" method="post" enctype="multipart/form-data">
        <?php echo wp_nonce_field( 'doregister_register', 'doregister_register_nonce', true, false ); ?>

<div class="aur-progress">
					<div class="aur-progress-line" style="width: 0%"></div>
					
			<div class="aur-step active" data-step="1">
				<div class="aur-step-number">1</div>
				<div class="aur-step-label">Basic Info</div>
			</div>
		
			<div class="aur-step " data-step="2">
				<div class="aur-step-number">2</div>
				<div class="aur-step-label">Contact</div>
			</div>
		
			<div class="aur-step " data-step="3">
				<div class="aur-step-number">3</div>
				<div class="aur-step-label">Personal</div>
			</div>
		
			<div class="aur-step " data-step="4">
				<div class="aur-step-number">4</div>
				<div class="aur-step-label">Photo</div>
			</div>
		
			<div class="aur-step " data-step="5">
				<div class="aur-step-number">5</div>
				<div class="aur-step-label">Review</div>
			</div>
		
				</div>
            <div class="step" data-step="1">
                <label>Full Name*<input type="text" name="full_name" required></label>
                <label>Email*<input type="email" name="email" required></label>
                <label>Password*<input type="password" name="password" required></label>
                <div class="password-strength" data-for="password">
                    <div class="strength-bar"></div>
                    <div class="strength-text"></div>
                </div>
                <label>Confirm Password*<input type="password" name="confirm_password" required></label>
                <div class="error" data-for="confirm_password"></div>
                <div class="error" data-for="password"></div>
                <div class="actions"><button type="button" class="next">Next</button></div>
            </div>

            <div class="step" data-step="2" style="display:none">
                <label>Phone*<input type="text" name="phone" required></label>
                <label>Country*<input type="text" name="country" required></label>
                <label>City<input type="text" name="city"></label>
                <div class="actions"><button type="button" class="back">Back</button> <button type="button" class="next">Next</button></div>
            </div>

            <div class="step" data-step="3" style="display:none">
                <label>Gender
                    <label><input type="radio" name="gender" value="male"> Male</label>
                    <label><input type="radio" name="gender" value="female"> Female</label>
                </label>
                <label>Date of Birth<input type="date" name="dob"></label>
                <label>Interests</label>
                <label><input type="checkbox" name="interests[]" value="sports"> Sports</label>
                <label><input type="checkbox" name="interests[]" value="music"> Music</label>
                <label><input type="checkbox" name="interests[]" value="reading"> Reading</label>
                <div class="actions"><button type="button" class="back">Back</button> <button type="button" class="next">Next</button></div>
            </div>

            <div class="step" data-step="4" style="display:none">
                <label>Upload Photo*<input type="file" name="photo" accept="image/*"></label>
                <input type="hidden" name="photo_id" value="">
                <div class="preview">
                    <img class="photo-preview" src="" style="display:none; max-width:200px;" alt="Preview">
                </div>
                <div class="actions"><button type="button" class="back">Back</button> <button type="button" class="next">Next</button></div>
            </div>

            <div class="step" data-step="5" style="display:none">
                <h3>Review & Confirm</h3>
                <div class="summary"></div>
                <div class="actions"><button type="button" class="back">Back</button> <button type="submit" class="submit">Submit</button></div>
            </div>
    </form>
</div>
