<?php

// Disable automatic plugin updates
add_filter( 'auto_update_plugin', '__return_false' );

// Disable dashboard widgets
add_action( 'wp_dashboard_setup', 'remove_default_widgets' );
function remove_default_widgets() {

    // Removes the quick press widget that allows you post right from the dashboard
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );

    // Removes the site health widget
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );

    // Removes the WordPress Blog widget
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}

add_action( 'admin_menu', 'remove_site_health_submenu', 999 );
function remove_site_health_submenu() {
        $page = remove_submenu_page( 'tools.php', 'site-health.php' );
}

// Reenable infinite scrolling in the media library
add_filter( 'media_library_infinite_scrolling', '__return_true' );

// Disable the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disable the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

// Disable XML RPC
// https://www.scottbrownconsulting.com/2020/03/two-ways-to-fully-disable-wordpress-xml-rpc/
function remove_xmlrpc_methods( $methods ) {
    return array();
}
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_methods' );

// Use youtube-nocookie.com for WP auto embeds
add_filter( 'embed_oembed_html', 'gdgts_youtube_nocookie_domain', 10, 4);

function gdgts_youtube_nocookie_domain( $html, $url, $attr, $post_ID ) {
	if ( preg_match('#https?://(www\.)?youtu#i', $url) ) {
		return preg_replace(
			'#src=(["\'])(https?:)?//(www\.)?youtube\.com#i',
			'src=$1$2//$3youtube-nocookie.com',
			$html
		);
	}
	return $html;
}
