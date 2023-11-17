<?php
/**
 * Changes for users profile.php.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme\User;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Register Profile.
 */
class Profile {

	/**
	 * Register all filters for altering the default user profile appearance.
	 *
	 * @return void
	 */
	public function initialize() {
		add_action( 'admin_head', array( $this, 'profile_admin_buffer_start' ) );
		add_action( 'admin_footer', array( $this, 'profile_admin_buffer_end' ) );
	}

	/**
	 * Start the output buffer.
	 *
	 * @return void
	 */
	public function profile_admin_buffer_start() {
		ob_start( array( $this, 'remove_profile_areas' ) );
	}

	/**
	 * Stop the output buffer.
	 *
	 * @return void
	 */
	public function profile_admin_buffer_end() {
		ob_end_flush();
	}

	/**
	 * Remove certain areas from the profile.php.
	 *
	 * @param  string $buffer Content of the output buffer.
	 * @return string Altered content.
	 */
	public function remove_profile_areas( $buffer ) {
		// Remove the "About Yourself" heading.
		$titles = array( '#<h2>About Yourself</h2>#', '#<h2>About the user</h2>#' );
		$buffer = preg_replace( $titles, '', $buffer, 1 );

		// Remove the "Biographical Info" box.
		$buffer = preg_replace( '/<tr class=\"user-description-wrap\"[\s\S]*?<\/tr>/', '', $buffer, 1 );

		// Remove the Gravatar box.
		$buffer = preg_replace( '/<tr class=\"user-profile-picture\"[\s\S]*?<\/tr>/', '', $buffer, 1 );
		return $buffer;
	}

}
