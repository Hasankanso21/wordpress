<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;






//validation: block reused passwords
function wp_prevent_password_reuse($errors, $update, $user) {
    if (empty($_POST['pass1'])) {
        return $errors;
    }

    global $wpdb;
    $password = $_POST['pass1'];

    // Fetch last 5 passwords
    $history = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT password_hash FROM {$wpdb->prefix}password_history 
             WHERE user_id = %d 
             ORDER BY changed_at DESC LIMIT 5",
            $user->ID
        )
    );

    foreach ($history as $old) {
        if (wp_check_password($password, $old->password_hash)) {
            $errors->add(
                'password_reuse',
                '<strong>Error:</strong> You cannot reuse one of your previous 5 passwords.'
            );
            break;
        }
    }

    return $errors;
}
add_filter('user_profile_update_errors', 'wp_prevent_password_reuse', 9, 3);


/* -------------------------------
 * Enforce Strong Password Policy
 * ------------------------------- */

function wp_enforce_strong_passwords( $errors, $update, $user ) {
    
    // Only validate when password is being changed
    if ( empty( $_POST['pass1'] ) ) {
        return $errors;
    }

    $password = $_POST['pass1'];

    // Length check
    if ( strlen( $password ) < 12 ) {
        $errors->add(
            'password_too_short',
            '<strong>Error:</strong> Password must be at least <strong>12 characters</strong> long.'
        );
    }

    // Uppercase check
    if ( ! preg_match( '/[A-Z]/', $password ) ) {
        $errors->add(
            'password_no_upper',
            '<strong>Error:</strong> Password must include at least <strong>one uppercase letter (A-Z)</strong>.'
        );
    }

    // Lowercase check
    if ( ! preg_match( '/[a-z]/', $password ) ) {
        $errors->add(
            'password_no_lower',
            '<strong>Error:</strong> Password must include at least <strong>one lowercase letter (a-z)</strong>.'
        );
    }

    // Number check
    if ( ! preg_match( '/[0-9]/', $password ) ) {
        $errors->add(
            'password_no_number',
            '<strong>Error:</strong> Password must include at least <strong>one number (0-9)</strong>.'
        );
    }

    // Special character check
    if ( ! preg_match( '/[\W_]/', $password ) ) {
        $errors->add(
            'password_no_special',
            '<strong>Error:</strong> Password must include at least <strong>one special character</strong> (e.g. ! @ # $ % ^ & *).'
        );
    }

    return $errors;
}
add_filter( 'user_profile_update_errors', 'wp_enforce_strong_passwords', 10, 3 );



/* ----------------------------------------------
 * Prevent Password Reuse (Password History)
 * ---------------------------------------------- */

function wp_store_password_history($user_id, $new_pass) {
    global $wpdb;

    // Hash the password like WordPress does
    $password_hash = wp_hash_password($new_pass);

    // Insert into history table
    $wpdb->insert(
        $wpdb->prefix . 'password_history',
        array(
            'user_id' => $user_id,
            'password_hash' => $password_hash
        ),
        array('%d', '%s')
    );

    // Keep only last 5 passwords
    $wpdb->query("
        DELETE FROM {$wpdb->prefix}password_history
        WHERE user_id = $user_id
        ORDER BY changed_at DESC
        LIMIT 100 OFFSET 5
    ");
}
add_action('after_password_reset', 'wp_store_password_history', 10, 2);
add_action('profile_update', function($user_id) {
    if (!empty($_POST['pass1'])) {
        wp_store_password_history($user_id, $_POST['pass1']);
    }
});

/* --------------------------------------------------------------
 * REQUIRE 2FA FOR ADMINISTRATORS
 * -------------------------------------------------------------- */

add_action('init', function () {

    // Only force 2FA inside wp-admin
    if ( ! is_admin() ) {
        return;
    }

    // Get current user
    $user = wp_get_current_user();

    // Must be logged in and must be administrator
    if ( ! $user || ! in_array('administrator', $user->roles) ) {
        return;
    }

    // Check if user has any 2FA method enabled
    $enabled_methods = Two_Factor_Core::get_enabled_providers_for_user( $user->ID );

    // If NO 2FA methods enabled → force redirect to profile page
    if ( empty( $enabled_methods ) ) {

        // Show error message only once
        if ( ! isset($_GET['force_2fa']) ) {
            wp_redirect( admin_url('profile.php?force_2fa=1') );
            exit;
        }
    }
});


/* --------------------------------------------------------------
 * SHOW ADMIN NOTICE FOR ADMINS WITHOUT 2FA ENABLED
 * -------------------------------------------------------------- */
add_action('admin_notices', function () {

    if ( ! is_admin() ) return;

    $user = wp_get_current_user();
    if ( ! $user || ! in_array('administrator', $user->roles) ) return;

    // Check 2FA status
    $enabled_methods = Two_Factor_Core::get_enabled_providers_for_user( $user->ID );

    // If the admin has already enabled 2FA → no message
    if ( ! empty($enabled_methods) ) return;

    // If admin has not enabled 2FA → show alert
    echo '
        <div class="notice notice-error">
            <p><strong>Security Notice:</strong> Two-Factor Authentication is required for all administrators.  
            Please configure it now to continue using the dashboard.</p>
        </div>
    ';
});




/* -------------------------------------------------------
 * Create custom 'staff' role with limited permissions
 * ------------------------------------------------------- */
function my_project_add_custom_roles() {
    if ( get_role('staff') ) return;

    add_role(
        'staff',
        'Staff',
        array(
            'read'         => true,
            'edit_posts'   => true,
            'upload_files' => true,
        )
    );
}
add_action('init', 'my_project_add_custom_roles');


/* -------------------------------------------------------
 * Restrict staff from sensitive wp-admin sections
 * ------------------------------------------------------- */
function my_project_limit_staff_admin_access() {

    if ( ! is_admin() ) return;
    if ( ! is_user_logged_in() ) return;

    $user = wp_get_current_user();

    // Only apply restrictions to STAFF
    if ( ! in_array('staff', (array) $user->roles) ) {
        return;
    }

    // Screens staff are NOT allowed to access
    $restricted = array(
        'users.php',
        'user-new.php',
        'plugins.php',
        'plugin-install.php',
        'tools.php',
        'options-general.php',
        'options-writing.php',
        'options-reading.php',
        'options-permalink.php',
        'themes.php',
        'customize.php',
        'update-core.php'
    );

    $current_page = $GLOBALS['pagenow'];

    if ( in_array( $current_page, $restricted, true ) ) {

        // Log denied access
        my_project_log_access('admin:' . $current_page, 'denied');

        wp_die(
            'Access denied. You do not have permission to view this page.',
            'Access Denied',
            array('response' => 403)
        );
    }
}
add_action('admin_init', 'my_project_limit_staff_admin_access');



/* -------------------------------------------------------
 * Log access attempts (works for both admin & staff)
 * ------------------------------------------------------- */
/* -------------------------------------------
 * Log access attempts to sensitive resources
 * ------------------------------------------- */
function my_project_log_access( $resource, $status ) {
    global $wpdb;

    $user_id   = is_user_logged_in() ? get_current_user_id() : null;
    $user_role = null;

    // Get role correctly
    if ( $user_id ) {
        $user  = wp_get_current_user();
        $roles = (array) $user->roles;
        $user_role = isset($roles[0]) ? $roles[0] : null;
    }

    $ip        = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

    // INSERT into wp_access_logs
    $wpdb->insert(
        $wpdb->prefix . 'access_logs',
        array(
            'user_id'    => $user_id,
            'user_role'  => $user_role,
            'resource'   => $resource,
            'status'     => $status,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        )
    );

    // DEBUG — Show any SQL error in the PHP error log
    if ( ! empty( $wpdb->last_error ) ) {
        error_log("SQL ERROR in my_project_log_access(): " . $wpdb->last_error);
    }
}


/* -------------------------------------------------------
 * Log ANY wp-admin screen visited by any logged-in user
 * ------------------------------------------------------- */
function my_project_log_admin_screen() {

    if ( ! is_user_logged_in() || ! is_admin() )
        return;

    $screen = get_current_screen();
    if ( ! $screen )
        return;

    // Log the full screen ID, e.g. "dashboard", "edit-post", "upload"
    my_project_log_access('admin:' . $screen->id, 'allowed');
}
add_action('current_screen', 'my_project_log_admin_screen');

