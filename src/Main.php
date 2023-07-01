<?php
/**
 * Main theme initialization.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme;

use IwarTheme\Translations;
use IwarTheme\WordPress;
use IwarTheme\Gutenberg;
use IwarTheme\Bbpress;
use IwarTheme\Import;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Initialize I-War theme.
 */
class Main {
	/**
	 * Registers various actions to load the
	 * main theme features at specific times.
	 *
	 * @return void
	 */
	public function initialize() {
		// Register translations before the main theme features.
		$translations = new Translations();
		add_action( 'init', array( $translations, 'register' ), 5 );

		// Initialize the main theme.
		add_action( 'init', array( $this, 'load_main' ), 10 );
	}

	/**
	 * Initializes the main theme features.
	 *
	 * @return void
	 */
	public function load_main() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_global_styles' ) );

		// Load various tweaks.
		add_action( 'init', array( $this, 'load_custom_tweaks' ), 20 );
	}


	/**
	 * Registers the plugin's main CSS file on the frontend.
	 *
	 * @return void
	 */
	public function enqueue_global_styles() {
		wp_register_style( 'iwar-main', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), '1.0' );
		wp_enqueue_style( 'iwar-main' );
	}

	/**
	 * Loads various tweaks for overriding core behaviour.
	 *
	 * @return void
	 */
	public function load_custom_tweaks() {
		// WordPress tweaks.
		$tweaks_wp = new WordPress();
		$tweaks_wp->initialize();

		// Gutenberg tweaks.
		$tweaks_gb = new Gutenberg();
		$tweaks_gb->initialize();

		// bbPress tweaks.
		$tweaks_bb = new Bbpress();
		$tweaks_bb->initialize();

		// Import functionality.
		$import = new Import();
		$import->initialize();
	}
}
