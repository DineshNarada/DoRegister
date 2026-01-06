<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="doregister-wrap">
    <form id="doregister-form" class="doregister-form" method="post" enctype="multipart/form-data">
        <?php echo wp_nonce_field( 'doregister_register', 'doregister_register_nonce', true, false ); ?>

            <div class="card-header">
                <h2>Create Your Account</h2>
                <p>Join our community in just a few steps</p>
            </div>

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
                <label>Country*
                    <div class="country-dropdown">
                        <input type="text" id="country-input" placeholder="Select Country" autocomplete="off">
                        <span class="dropdown-arrow">▼</span>
                        <ul id="country-list" style="display:none;">
                            <?php
                            foreach ($countries as $country => $code) {
                                echo '<li class="country-item" data-code="' . esc_attr($code) . '">' . esc_html($country) . '</li>';
                            }
                            ?>
                        </ul>
                        <input type="hidden" name="country" id="country-hidden" required>
                    </div>
                </label>
                <div class="error" data-for="country"></div>
                <label>City<input type="text" name="city"></label>
                <label>Phone*<input type="text" name="phone" required></label>
                <div class="error" data-for="phone"></div>
                <div class="actions"><button type="button" class="back">Back</button> <button type="button" class="next">Next</button></div>
            </div>

            <div class="step" data-step="3" style="display:none">
                <p>Gender</p>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="male"> Male</label>
                    <label><input type="radio" name="gender" value="female"> Female</label>
                </div>
                <label>Date of Birth<input type="date" name="dob" placeholder="YYYY-MM-DD"></label>
                <p>Interests</p>
                <div class="interests-grid">
                    <label><input type="checkbox" name="interests[]" value="sports"> 🏀 Sports</label>
                    <label><input type="checkbox" name="interests[]" value="music"> 🎵 Music</label>
                    <label><input type="checkbox" name="interests[]" value="reading"> 📖 Reading</label>
                    <label><input type="checkbox" name="interests[]" value="travel"> ✈️ Travel</label>
                    <label><input type="checkbox" name="interests[]" value="cooking"> 🍳 Cooking</label>
                    <label><input type="checkbox" name="interests[]" value="art"> 🎨 Art</label>
                    <label><input type="checkbox" name="interests[]" value="technology"> 💻 Technology</label>
                    <label><input type="checkbox" name="interests[]" value="gaming"> 🎮 Gaming</label>
                    <label><input type="checkbox" name="interests[]" value="movies"> 🎬 Movies</label>
                    <label><input type="checkbox" name="interests[]" value="fitness"> 💪 Fitness</label>
                </div>
                <label>Other Interests<input type="text" name="other_interests" placeholder="Specify other interests"></label>
                <div class="actions"><button type="button" class="back">Back</button> <button type="button" class="next">Next</button></div>
            </div>

            <div class="step" data-step="4" style="display:none">
                <div class="aur-form-group">
                    <label>Profile Photo <span class="aur-required">*</span></label>
                    <div class="aur-file-upload" id="aur-file-upload" tabindex="0">
                        <input type="file" name="photo" accept="image/*" id="aur-photo-input" style="display:none">
                        <div class="aur-upload-icon">
                            <img draggable="false" role="img" class="emoji" alt="📷" src="https://s.w.org/images/core/emoji/17.0.2/svg/1f4f7.svg">
                        </div>
                        <div class="aur-upload-text">Click to upload or drag &amp; drop<br>JPG, PNG or GIF (max 2MB)</div>
                    </div>
                    <input type="hidden" name="photo_id" value="">
                    <div class="preview">
                        <img class="photo-preview" src="" style="display:none; max-width:200px;" alt="Preview">
                    </div>
                    <div class="aur-error-message" data-field="photo"></div>
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
