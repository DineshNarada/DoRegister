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

        // Profile update
        function showProfileMessage( selector, text, isError ) {
            var el = $( selector );
            el.text( text ).removeClass('success error');
            el.addClass( isError ? 'error' : 'success' );
            setTimeout(function() { el.text(''); }, 5000 );
        }

        function uploadPhoto(file) {
            var fd = new FormData();
            fd.append('action', 'doregister_upload');
            fd.append('nonce', DrAjax.upload_nonce);
            fd.append('photo', file);

            return $.ajax({
                url: DrAjax.ajax_url,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false
            });
        }

        function updateProfile(data) {
            data = data || {};
            var post = {
                action: 'doregister_profile_update',
                nonce: DrAjax.profile_nonce,
                data: data
            };
            return $.post( DrAjax.ajax_url, post );
        }

        $('#doregister-save-profile').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true);

            var file = $('#dr-photo')[0].files[0];
            var data = {
                full_name: $('#dr-full-name').val(),
                email: $('#dr-email').val(),
                password: $('#dr-password').val(),
                phone: $('#dr-phone').val(),
                country: $('#dr-country').val(),
                city: $('#dr-city').val(),
                gender: $('#dr-gender').val(),
                dob: $('#dr-dob').val(),
                interests: $('#dr-interests').val(),
                profile_photo_id: $('#dr-photo-id').val()
            };

            var promise;
            if ( file ) {
                promise = uploadPhoto( file ).then(function( res ) {
                    if ( res.success ) {
                        data.profile_photo_id = res.data.attachment_id;
                        $('#dr-photo-id').val( res.data.attachment_id );
                    } else {
                        return $.Deferred().reject( res );
                    }
                });
            } else {
                promise = $.Deferred().resolve();
            }

            promise.then(function() {
                return updateProfile( data );
            }).done(function( res ) {
                if ( res.success ) {
                    showProfileMessage( '#dr-profile-message', res.data.message || 'Saved.', false );
                    // update preview photo if present
                    if ( data.profile_photo_id ) {
                        var url = $('#dr-photo-preview img').attr('src');
                        // fetch new thumbnail via AJAX or refresh the page for simplicity
                        setTimeout(function(){ window.location.reload(); }, 800 );
                    }
                } else {
                    showProfileMessage( '#dr-profile-message', res.data.message || 'An error occurred.', true );
                }
            }).fail(function( err ) {
                var msg = 'Error saving profile.';
                if ( err && err.responseJSON && err.responseJSON.data && err.responseJSON.data.message ) {
                    msg = err.responseJSON.data.message;
                } else if ( err && err.responseText ) {
                    try { msg = JSON.parse( err.responseText ).data.message; } catch(e){}
                }
                showProfileMessage( '#dr-profile-message', msg, true );
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });

        // Delete account
        $('#doregister-delete-account').on('click', function() {
            if ( ! confirm('Are you sure you want to permanently delete your account? This cannot be undone.') ) {
                return;
            }
            var $btn = $(this);
            $btn.prop('disabled', true);
            var pwd = $('#dr-delete-password').val();
            $.post( DrAjax.ajax_url, { action: 'doregister_delete_account', nonce: DrAjax.delete_nonce, password: pwd } ).done(function( res ) {
                if ( res.success ) {
                    showProfileMessage( '#dr-delete-message', res.data.message || 'Account deleted. Redirecting...', false );
                    setTimeout(function() { window.location.href = '/'; }, 1200 );
                } else {
                    showProfileMessage( '#dr-delete-message', res.data.message || 'Error deleting account.', true );
                }
            }).fail(function( err ) {
                var msg = 'Error deleting account.';
                if ( err && err.responseJSON && err.responseJSON.data && err.responseJSON.data.message ) {
                    msg = err.responseJSON.data.message;
                }
                showProfileMessage( '#dr-delete-message', msg, true );
            }).always(function() { $btn.prop('disabled', false); });
        });
    });
})(jQuery);