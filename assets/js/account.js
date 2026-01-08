(function($) {
    $(document).ready(function() {
        // Tab switching for login/register on account page
        $(document).on('click', '.doregister-tab', function() {
            var tab = $(this).data('tab');

            // Update tab states
            $('.doregister-tab').removeClass('active');
            $(this).addClass('active');

            // Update content states
            $('.doregister-tab-content').removeClass('active');
            $('.doregister-tab-content[data-tab="' + tab + '"]').addClass('active');
        });

        // Handle successful login - redirect to account page or reload
        $(document).on('doregister_login_success', function(event, response) {
            // Login successful, redirect to account page or reload
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        });

        // Profile photo modal
        $(document).on('click', '.profile-photo', function() {
            var photoId = $(this).data('photo-id');
            if (photoId) {
                var imageUrl = $(this).find('img').attr('src');
                $('#modal-image').attr('src', imageUrl);
                $('.photo-modal').show();
            }
        });

        $(document).on('click', '.modal-close', function() {
            $('.photo-modal').hide();
        });

        $(document).on('click', '.photo-modal', function(e) {
            if (e.target === this) {
                $('.photo-modal').hide();
            }
        });

        // Edit profile
        $(document).on('click', '.edit-profile-btn', function() {
            $('.edit-mode-overlay').show();
        });

        $(document).on('click', '.cancel-edit-btn', function() {
            $('.edit-mode-overlay').hide();
        });

        $(document).on('click', '.file-upload-btn', function() {
            $('#edit_photo').click();
        });

        $(document).on('change', '#edit_photo', function() {
            var file = this.files[0];
            if (file) {
                $('.file-name').text(file.name);
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-img').attr('src', e.target.result);
                    $('#image-preview').show();
                };
                reader.readAsDataURL(file);
            } else {
                $('.file-name').text('No file chosen');
                $('#image-preview').hide();
            }
        });

        $(document).on('submit', '#edit-profile-form', function(e) {
            var formData = new FormData(this);
            formData.append('action', 'doregister_update_profile');
            formData.append('nonce', doregister_ajax.nonce);

            $.ajax({
                url: doregister_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#message').removeClass('error').addClass('success').text(response.data.message).show();
                        $('.edit-mode-overlay').hide();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        $('#message').removeClass('success').addClass('error').text(response.data.message).show();
                    }
                },
                error: function() {
                    $('#message').removeClass('success').addClass('error').text('An error occurred.').show();
                }
            });
        });

        // Change password
        $(document).on('click', '.change-password-btn', function() {
            $('.change-password-form').slideToggle();
        });

        $(document).on('click', '.cancel-password-btn', function() {
            $('.change-password-form').slideUp();
        });

        $(document).on('submit', '#change-password-form', function(e) {
            e.preventDefault();
            var data = {
                action: 'doregister_change_password',
                nonce: doregister_ajax.change_password_nonce,
                data: $(this).serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {})
            };

            $.ajax({
                url: doregister_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        $('#message').removeClass('error').addClass('success').text(response.data.message).show();
                        $('.change-password-form').slideUp();
                        setTimeout(function() {
                            window.location.href = doregister_ajax.logout_redirect_url;
                        }, 2000);
                    } else {
                        $('#message').removeClass('success').addClass('error').text(response.data.message).show();
                    }
                },
                error: function() {
                    $('#message').removeClass('success').addClass('error').text('An error occurred.').show();
                }
            });
        });
    });
})(jQuery);