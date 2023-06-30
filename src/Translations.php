<?php
/**
 * Theme translations.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Registers theme translations.
 */
class Translations {
	/**
	 * Register .mo language files.
	 *
	 * @return void
	 */
	public function register() {
		load_theme_textdomain( 'iwar-theme', get_stylesheet_directory() . '/languages/' );
	}
}
