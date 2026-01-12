<?php
namespace DoRegister;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Plugin {
    public static $file;

    public static function init( $file ) {
        self::$file = $file;
        self::includes();

        add_action( 'plugins_loaded', [ __CLASS__, 'setup' ] );
        register_activation_hook( $file, [ __CLASS__, 'activate' ] );
        register_deactivation_hook( $file, [ __CLASS__, 'deactivate' ] );
    }

    protected static function includes() {
        $dir = plugin_dir_path( self::$file );
        require_once $dir . 'includes/class-assets.php';
        require_once $dir . 'includes/class-ajax.php';
        require_once $dir . 'includes/class-registration.php';
        require_once $dir . 'includes/class-login.php';
        require_once $dir . 'includes/class-profile.php';
        require_once $dir . 'includes/admin-user-management.php';
    }

    public static function setup() {
        // Instantiate core pieces
        new Assets( self::$file );
        new Ajax();
        new AdminUserManagement();
        // Register dashboard CTA, admin menu and admin styles
        add_action( 'wp_dashboard_setup', [ __CLASS__, 'register_dashboard_cta' ] );
        add_action( 'admin_menu', [ __CLASS__, 'add_admin_cta_menu' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_admin_settings' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_cta_styles' ] );
        // Registration, Login, Profile register shortcodes
        add_shortcode( 'doregister_form', [ '\\DoRegister\\Registration', 'render_shortcode' ] );
        add_shortcode( 'doregister_login', [ '\\DoRegister\\Login', 'render_shortcode' ] );
        add_shortcode( 'doregister_profile', [ '\\DoRegister\\Profile', 'render_shortcode' ] );
        add_shortcode( 'doregister_account', [ __CLASS__, 'render_account_shortcode' ] );
    }

    public static function register_dashboard_cta() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        wp_add_dashboard_widget(
            'doregister_cta_widget',
            'DoRegister — Get Started',
            [ __CLASS__, 'render_dashboard_cta' ]
        );
    }

    public static function render_dashboard_cta() {
        $admin_link = admin_url( 'users.php?page=doregister-users' );
        $docs_link = 'https://example.com/docs/doregister';
        ?>
        <div class="doregister-cta">
            <p style="margin:0 0 12px;">Quick actions to get started with DoRegister.</p>
            <p style="margin:0 0 12px;"><a href="<?php echo esc_url( $admin_link ); ?>" class="button button-primary button-hero">Open DoRegister Users</a>
            <a href="<?php echo esc_url( $docs_link ); ?>" target="_blank" class="button">Plugin Docs</a></p>
            <p style="margin:0;font-size:13px;color:#666;">Tip: Use the <code>[doregister_form]</code> shortcode on any page to show the registration form.</p>
        </div>
        <?php
    }

    public static function add_admin_cta_menu() {
        add_menu_page(
            'DoRegister',
            'DoRegister',
            'manage_options',
            'doregister-cta',
            [ __CLASS__, 'render_admin_cta_page' ],
            'dashicons-admin-users',
            58
        );

        // Add a Settings submenu under our top-level DoRegister menu
        add_submenu_page(
            'doregister-cta',
            'DoRegister Settings',
            'Settings',
            'manage_options',
            'doregister-settings',
            [ __CLASS__, 'render_admin_settings_page' ]
        );
    }

    public static function render_admin_cta_page() {
        $users_link = admin_url( 'users.php?page=doregister-users' );
        $settings_link = admin_url( 'admin.php?page=doregister-settings' );
        $shortcode = '[doregister_form]';
        ?>
        <div class="wrap">
            <h1>DoRegister</h1>
            <div class="doregister-cta" style="max-width:800px;">
                <p style="margin:0 0 12px;">Ready to use DoRegister? Quick actions below.</p>
                <p style="margin:0 0 12px;">
                    <a href="<?php echo esc_url( $users_link ); ?>" class="button button-primary button-hero">Manage DoRegister Users</a>
                </p>
                <p style="margin:0;font-size:13px;color:#666;">Place the shortcode <code><?php echo esc_html( $shortcode ); ?></code> on any page to show the registration form.</p>
            </div>
        </div>
        <?php
    }

    public static function register_admin_settings() {
        // Register a single options array for DoRegister
        register_setting( 'doregister_options_group', 'doregister_options', [ __CLASS__, 'sanitize_options' ] );

        add_settings_section(
            'doregister_main_section',
            'General Settings',
            [ __CLASS__, 'render_settings_section' ],
            'doregister-settings'
        );

        add_settings_field(
            'redirect_url',
            'Redirect URL after registration',
            [ __CLASS__, 'render_field_redirect_url' ],
            'doregister-settings',
            'doregister_main_section'
        );
    }

    public static function sanitize_options( $input ) {
        $output = [];
        $output['redirect_url'] = isset( $input['redirect_url'] ) ? esc_url_raw( trim( $input['redirect_url'] ) ) : '';
        return $output;
    }

    public static function render_settings_section() {
        echo '<p>Configure DoRegister behavior.</p>';
    }

    public static function render_field_redirect_url() {
        $opts = get_option( 'doregister_options', [] );
        $val = $opts['redirect_url'] ?? '';
        printf( '<input type="url" name="doregister_options[redirect_url]" value="%s" class="regular-text" />', esc_attr( $val ) );
        echo '<p class="description">URL to redirect users to after successful registration.</p>';
    }

    public static function render_admin_settings_page() {
        ?>
        <div class="wrap">
            <h1>DoRegister Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'doregister_options_group' );
                do_settings_sections( 'doregister-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function enqueue_admin_cta_styles( $hook ) {
        // Only load on Dashboard, CTA page, or settings page
        if ( 'index.php' !== $hook && 'toplevel_page_doregister-cta' !== $hook && 'doregister_page_doregister-settings' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'doregister-admin-cta', plugin_dir_url( self::$file ) . 'assets/css/admin-cta.css', [], '1.0.0' );
    }

    public static function activate() {
        // Activation tasks (if any)
    }

    public static function deactivate() {
        // Deactivation tasks (if any)
    }

    public static function render_account_shortcode() {
        if ( ! is_user_logged_in() ) {
            // Show login and register options
            $login_content = do_shortcode( '[doregister_login]' );
            $register_content = do_shortcode( '[doregister_form]' );
            ob_start();
            ?>
            <div class="doregister-account-page">
                <h2>Account</h2>
                <div class="doregister-account-tabs">
                    <button class="doregister-tab active" data-tab="login">Login</button>
                    <button class="doregister-tab" data-tab="register">Register</button>
                </div>
                <div class="doregister-tab-content active" data-tab="login">
                    <?php echo $login_content; ?>
                </div>
                <div class="doregister-tab-content" data-tab="register">
                    <?php echo $register_content; ?>
                </div>
            </div>
            <?php
            return ob_get_clean();
        } else {
            // Show profile and logout options
            $profile_content = do_shortcode( '[doregister_profile]' );
            ob_start();
            ?>
            <div class="doregister-account-page">
                <h2>My Account</h2>
                <?php echo $profile_content; ?>
                
            </div>
            <?php
            return ob_get_clean();
        }
    }
}
