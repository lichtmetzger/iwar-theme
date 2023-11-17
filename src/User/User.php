<?php
/**
 * Register User methods.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme\User;

use IwarTheme\User\ContactMethods;
use IwarTheme\User\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Register User.
 */
class User {

	/**
	 * Register all filters and hooks.
	 *
	 * @return void
	 */
	public function __construct() {
		$cm = new ContactMethods();
		$cm->initialize();

		$pr = new Profile();
		$pr->initialize();
	}
}
