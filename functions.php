<?php

defined( 'ABSPATH' ) || exit;

use DisableImageSrcset\Plugin;

// region

/**
 * Returns the plugin's main class instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  Plugin
 */
function disable_image_srcset_get_plugin_instance(): Plugin {
	return Plugin::get_instance();
}

/**
 * Returns the plugin's slug.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function disable_image_srcset_get_plugin_slug(): string {
	return sanitize_key( DISABLE_IMAGE_SRCSET_METADATA['TextDomain'] );
}

// endregion
