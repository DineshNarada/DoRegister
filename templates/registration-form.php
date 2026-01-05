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
                            require_once dirname(__DIR__) . '/assets/countries.php';
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
                    <label><input type="checkbox" name="interests[]" value="sports"> Sports</label>
                    <label><input type="checkbox" name="interests[]" value="music"> Music</label>
                    <label><input type="checkbox" name="interests[]" value="reading"> Reading</label>
                    <label><input type="checkbox" name="interests[]" value="travel"> Travel</label>
                    <label><input type="checkbox" name="interests[]" value="cooking"> Cooking</label>
                    <label><input type="checkbox" name="interests[]" value="art"> Art</label>
                    <label><input type="checkbox" name="interests[]" value="technology"> Technology</label>
                    <label><input type="checkbox" name="interests[]" value="gaming"> Gaming</label>
                    <label><input type="checkbox" name="interests[]" value="movies"> Movies</label>
                    <label><input type="checkbox" name="interests[]" value="fitness"> Fitness</label>
                </div>
                <label>Other Interests<input type="text" name="other_interests" placeholder="Specify other interests"></label>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('country-input');
    const list = document.getElementById('country-list');
    const hidden = document.getElementById('country-hidden');
    const countries = <?php echo json_encode(array_keys($countries)); ?>;

    input.addEventListener('focus', () => {
        list.style.display = 'block';
    });

    input.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const items = list.querySelectorAll('.country-item');
        items.forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(filter) ? 'block' : 'none';
        });
        list.style.display = 'block';
    });

    input.addEventListener('blur', function() {
        if (!countries.includes(this.value)) {
            this.value = '';
            hidden.value = '';
        }
    });

    list.addEventListener('click', function(e) {
        if (e.target.classList.contains('country-item')) {
            input.value = e.target.textContent;
            hidden.value = e.target.textContent;
            const code = e.target.getAttribute('data-code');
            const phoneInput = document.querySelector('input[name="phone"]');
            phoneInput.value = code;
            phoneInput.focus();
            list.style.display = 'none';
        }
    });

    // Hide list when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.style.display = 'none';
        }
    });
});
</script>
