(function($){
    $(function(){
        var $form = $('#doregister-login-form');
        if (!$form.length) return;

        $form.on('submit', function(e){
            e.preventDefault();
            var $button = $form.find('button[type="submit"]');
            var originalText = $button.text();
            $button.prop('disabled', true).text('Logging in...');
            $('.login-message').text('').removeClass('error success');

            var data = {
                action: 'doregister_login',
                nonce: DrAjax.login_nonce,
                user: $form.find('[name="user"]').val(),
                pass: $form.find('[name="pass"]').val()
            };

            $.post(DrAjax.ajax_url, data, function(resp){
                if (resp.success){
                    $('.login-message').text(resp.data.message || 'Logged in').addClass('success');
                    // Trigger custom event for popup handling
                    $(document).trigger('doregister_login_success', [resp]);
                    setTimeout(function(){
                        window.location = resp.data.redirect || window.location.href;
                    }, 1000);
                } else {
                    $('.login-message').text(resp.data && resp.data.message ? resp.data.message : 'Login failed').addClass('error');
                }
            }).fail(function(){
                $('.login-message').text('Request failed').addClass('error');
            }).always(function(){
                $button.prop('disabled', false).text(originalText);
            });
        });
    });
})(jQuery);
