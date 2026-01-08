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
    });
})(jQuery);