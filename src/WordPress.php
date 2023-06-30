<?php
/**
 * WordPress core behaviour overrides.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Registers WordPress tweaks.
 */
class WordPress {
	/**
	 * Initialization function.
	 *
	 * @return void
	 */
	public function initialize() {
		// Disable automatic plugin updates.
		add_filter( 'auto_update_plugin', '__return_false' );

		// Reenable infinite scrolling in the media library.
		add_filter( 'media_library_infinite_scrolling', '__return_true' );

		// Disable the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

		// Disable the block editor from managing widgets.
		add_filter( 'use_widgets_block_editor', '__return_false' );

		// Disable dashboard widgets.
		add_filter(
			'wp_dashboard_setup',
			function() {
				// Removes the quick press widget that allows posting directly from the dashboard.
				remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );

				// Removes the site health widget.
				remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );

				// Removes the WordPress Blog widget.
				remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
			}
		);

		// Remove the Site Health sub menu.
		add_action(
			'admin_menu',
			function() {
				$page = remove_submenu_page( 'tools.php', 'site-health.php' );
			},
			999
		);

		// Disable XML RPC.
		add_filter(
			'xmlrpc_methods',
			function( $methods ) {
				return array();
			}
		);

		// Use youtube-nocookie.com for WP auto embeds.
		add_filter(
			'embed_oembed_html',
			function() {
				if ( preg_match( '#https?://(www\.)?youtu#i', $url ) ) {
					return preg_replace(
						'#src=(["\'])(https?:)?//(www\.)?youtube\.com#i',
						'src=$1$2//$3youtube-nocookie.com',
						$html
					);
				}
				return $html;
			},
			10,
			4
		);

	}
}
