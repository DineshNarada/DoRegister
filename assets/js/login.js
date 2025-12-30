(function($){
    $(function(){
        var $form = $('#doregister-login-form');
        if (!$form.length) return;

        $form.on('submit', function(e){
            e.preventDefault();
            var data = {
                action: 'doregister_login',
                nonce: DrAjax.login_nonce,
                user: $form.find('[name="user"]').val(),
                pass: $form.find('[name="pass"]').val()
            };

            $.post(DrAjax.ajax_url, data, function(resp){
                if (resp.success){
                    $('.login-message').text(resp.data.message || 'Logged in');
                    window.location = resp.data.redirect || window.location.href;
                } else {
                    $('.login-message').text(resp.data && resp.data.message ? resp.data.message : 'Login failed');
                }
            }).fail(function(){ $('.login-message').text('Request failed'); });
        });
    });
})(jQuery);
