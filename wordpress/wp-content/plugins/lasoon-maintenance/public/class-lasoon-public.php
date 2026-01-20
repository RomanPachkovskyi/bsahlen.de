<?php
/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Lasoon
 * @subpackage Lasoon/public
 * @author     The_Krishna
 */
class Lasoon_Public {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( !empty( get_option('lasoon_maintenance_mode') ) && esc_attr(get_option('lasoon_maintenance_mode') === 'Unable') ){

			if (
				( ! $this->check_user_role() ) &&
				! strstr( $_SERVER['PHP_SELF'], 'wp-cron.php' ) &&
				! strstr( $_SERVER['PHP_SELF'], 'wp-login.php' ) &&
			// wp-admin/ is available to everyone only if the user is not loggedin, otherwise.. check_user_role decides
				! ( strstr( $_SERVER['PHP_SELF'], 'wp-admin/' ) && ! is_user_logged_in() ) &&
				! strstr( $_SERVER['PHP_SELF'], 'wp-admin/admin-ajax.php' ) &&
				! strstr( $_SERVER['PHP_SELF'], 'async-upload.php' ) &&
				! ( strstr( $_SERVER['PHP_SELF'], 'upgrade.php' ) && $this->check_user_role() ) &&
				! strstr( $_SERVER['PHP_SELF'], '/plugins/' ) &&
				! strstr( $_SERVER['PHP_SELF'], '/xmlrpc.php' ) &&
				! ( defined( 'WP_CLI' ) && WP_CLI ) &&
				esc_attr(get_option('lasoon_maintenance_mode') === 'Unable')

			) {
				add_action( 'template_redirect'  , array( $this, 'init' ) );
			} 
		}
	}


	 /**
 	   * Enqueue a script with jQuery as a dependency.
 	   */

	 function add_lasoon_public_scripts_func( $hook_suffix ) {
	 	
	 	add_filter('wp_mail_content_type', function( $content_type ) {
	 		return 'text/html';
	 	});

	 }


		/**
		 * Initialize when plugin is activated
		 *
		 * @since 1.0.0
		 */
		public function init() {
			/**
			 * CHECKS
			 */

			if (
				( ! $this->check_user_role() ) &&
				! strstr( $_SERVER['PHP_SELF'], 'wp-cron.php' ) &&
				! strstr( $_SERVER['PHP_SELF'], 'wp-login.php' ) &&
			// wp-admin/ is available to everyone only if the user is not loggedin, otherwise.. check_user_role decides
				! ( strstr( $_SERVER['PHP_SELF'], 'wp-admin/' ) && ! is_user_logged_in() ) &&
				! strstr( $_SERVER['PHP_SELF'], 'wp-admin/admin-ajax.php' ) &&
				! strstr( $_SERVER['PHP_SELF'], 'async-upload.php' ) &&
				! ( strstr( $_SERVER['PHP_SELF'], 'upgrade.php' ) && $this->check_user_role() ) &&
				! strstr( $_SERVER['PHP_SELF'], '/plugins/' ) &&
				! strstr( $_SERVER['PHP_SELF'], '/xmlrpc.php' ) &&
				! ( defined( 'WP_CLI' ) && WP_CLI ) &&
				esc_attr(get_option('lasoon_maintenance_mode') === 'Unable')

			) {
			// HEADER STUFF
				$protocol         = ! empty( $_SERVER['SERVER_PROTOCOL'] ) && in_array( $_SERVER['SERVER_PROTOCOL'], array( 'HTTP/1.1', 'HTTP/1.0' ), true ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
				$charset          = get_bloginfo( 'charset' ) ? get_bloginfo( 'charset' ) : 'UTF-8';
				$status_code      = (int) apply_filters( 'lmm_status_code', 503 );
				$backtime         = get_option('lasoon_launch_date')? esc_attr(get_option('lasoon_launch_date')): '';

			//META STUFF
				$title = ! empty( get_option('lasoon_seo_title') ) ? esc_attr(get_option('lasoon_seo_title')) : get_bloginfo( 'name' ) . ' - ' . __( 'Maintenance Mode', 'lasoon' );
				$title = apply_filters( 'lcm_meta_title', $title );

				$robots = get_option('lasoon_meta_robot') === 'index' ? 'index, follow': 'noindex, nofollow';
				$robots = apply_filters( 'lcm_meta_robots', $robots );

				$author = apply_filters( 'lcm_meta_author', get_bloginfo( 'name' ) );

				$description = get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
				$description = apply_filters( 'lcm_meta_description', $description );

				$keywords = _x( 'Maintenance Mode', '<meta> keywords default', 'lasoon' );
			$keywords = apply_filters( 'wm_meta_keywords', $keywords ); // this hook will be removed in the next versions
			$keywords = apply_filters( 'lcm_meta_keywords', $keywords );

			//CSS STUFF
			$body_classes = !empty( get_option('lasoon_back_animation') )  ? esc_attr(get_option('lasoon_back_animation')) : 'particles';
			$body_classes = apply_filters( 'lcm_body_classes', $body_classes );
			// Counter Shape
			$counter_shape = !empty( get_option('lasoon_shape_animation') )  ? esc_attr(get_option('lasoon_shape_animation')) : 'dash_circle';
			$counter_shape = apply_filters( 'lcm_counter_shape', $counter_shape );


			$this->lcm_set_nocache_constants();
			nocache_headers();

			ob_start();
			header( "Content-type: text/html; charset=$charset" );
			header( "$protocol $status_code Service Unavailable", true, $status_code );
			header( "Retry-After: $backtime" );

			// load maintenance mode template
			include_once 'coming-soon.php';
			ob_flush();

			exit();
		}
	}

	/**
	* Check if the current user has access to backend / frontend based on his role compared with role from settings (refactor @ 2.0.4)
	*
	* @since 1.0.0
	* @return boolean
	*/
	public function check_user_role() {
	// check super admin (when multisite is activated) / check admin (when multisite is not activated)
		if ( is_super_admin() ) {
			return true;
		}
		$frontend_roles = get_option('lasoon_exclude_frontend');
		$backend_roles = get_option('lasoon_exclude_backend');

		$user          = wp_get_current_user();
		$user_roles    = ! empty( $user->roles ) && is_array( $user->roles ) ? $user->roles : array();
		$allowed_roles = array();
		$allowed_roles = is_admin() ? (array) $backend_roles  : (array) $frontend_roles;

// add `administrator` role when multisite is activated and the admin of a blog is trying to access his blog
		if ( is_multisite() || is_admin()) {
			array_push( $allowed_roles, 'administrator' );
		}
		$is_allowed = (bool) array_intersect( $user_roles, $allowed_roles );
		return $is_allowed;
	}


/**
* Define constants
*
* @since 1.0.0
*/

public function lcm_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
* Set nocache constants
*
* @since 1.0.0
*/
public function lcm_set_nocache_constants() {
	$this->lcm_maybe_define_constant( 'DONOTCACHEPAGE', true );
	$this->lcm_maybe_define_constant( 'DONOTCACHEOBJECT', true );
	$this->lcm_maybe_define_constant( 'DONOTCACHEDB', true );
	$this->lcm_maybe_define_constant( 'DONOTMINIFY', true );
	$this->lcm_maybe_define_constant( 'DONOTCDN', true );
}
}


new lasoon_Public();