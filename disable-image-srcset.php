<?php
/**
 * The Disable Image Srcset bootstrap file.
 *
 * @since       1.0.0
 * @version     1.0.0
 * @author      WordPress.com Special Projects
 * @license     GPL-3.0-or-later
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             Disable Image Srcset
 * Plugin URI:              https://wpspecialprojects.wordpress.com
 * Description:             Gives you the ability to disable srcset on specific attachments
 * Version:                 1.0.0
 * Requires at least:       6.2
 * Tested up to:            6.2
 * Requires PHP:            8.0
 * Author:                  WordPress.com Special Projects
 * Author URI:              https://wpspecialprojects.wordpress.com
 * License:                 GPL v3 or later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:             disable-image-srcset
 * Domain Path:             /languages
 * WC requires at least:    7.4
 * WC tested up to:         7.4
 **/

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'DISABLE_IMAGE_SRCSET_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'DISABLE_IMAGE_SRCSET_BASENAME', plugin_basename( __FILE__ ) );
define( 'DISABLE_IMAGE_SRCSET_PATH', plugin_dir_path( __FILE__ ) );
define( 'DISABLE_IMAGE_SRCSET_URL', plugin_dir_url( __FILE__ ) );

// Load plugin translations so they are available even for the error admin notices.
add_action(
	'init',
	static function() {
		load_plugin_textdomain(
			DISABLE_IMAGE_SRCSET_METADATA['TextDomain'],
			false,
			dirname( DISABLE_IMAGE_SRCSET_BASENAME ) . DISABLE_IMAGE_SRCSET_METADATA['DomainPath']
		);
	}
);

// Load the autoloader.
if ( ! is_file( DISABLE_IMAGE_SRCSET_PATH . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function() {
			$message      = __( 'It seems like <strong>Disable Image Srcset</strong> is corrupted. Please reinstall!', 'disable-image-srcset' );
			$html_message = wp_sprintf( '<div class="error notice disable-image-srcset-error">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
	);
	return;
}
require_once DISABLE_IMAGE_SRCSET_PATH . '/vendor/autoload.php';

// Initialize the plugin if system requirements check out.
$disable_image_srcset_requirements = validate_plugin_requirements( DISABLE_IMAGE_SRCSET_BASENAME );
define( 'DISABLE_IMAGE_SRCSET_REQUIREMENTS', $disable_image_srcset_requirements );

if ( $disable_image_srcset_requirements instanceof WP_Error ) {
	add_action(
		'admin_notices',
		static function() use ( $disable_image_srcset_requirements ) {
			$html_message = wp_sprintf( '<div class="error notice disable-image-srcset-error">%s</div>', $disable_image_srcset_requirements->get_error_message() );
			echo wp_kses_post( $html_message );
		}
	);
} else {
	require_once DISABLE_IMAGE_SRCSET_PATH . 'functions.php';
	add_action( 'plugins_loaded', array( disable_image_srcset_get_plugin_instance(), 'initialize' ) );
}
