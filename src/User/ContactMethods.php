<?php
/**
 * Customize the contact method fields available to a user in their profile.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme\User;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Register ProfileFields.
 */
class ContactMethods {

	/**
	 * Register all filters for altering the default contact method fields.
	 *
	 * @return void
	 */
	public function initialize() {
		add_filter( 'user_contactmethods', array( $this, 'customize_contact_methods' ), 10, 1 );
		// Add action to save the new biographical info.
		add_action( 'personal_options_update', array( $this, 'save_custom_contact_methods' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_custom_contact_methods' ) );
	}

	/**
	 * Customize user contact method fields.
	 *
	 * @param  array $methods Contact methods.
	 * @return array Overridden methods.
	 */
	public function customize_contact_methods( $methods ) {
		// Add a new text field for the personal text.
		$methods['personal_text'] = 'Personal Text';

		// Add a new text field for the location.
		$methods['location'] = 'Location';

		return $methods;
	}

	/**
	 * Save submitted data into user meta.
	 *
	 * @param  int $user_id The user ID.
	 * @return bool Meta updated or not.
	 */
	public function save_custom_contact_methods( $user_id ) {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'update-user_' . $user_id ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( isset( $_POST['personal_text'] ) ) {
			update_user_meta( $user_id, 'personal_text', sanitize_text_field( wp_unslash( $_POST['personal_text'] ) ) );
		}

		if ( isset( $_POST['location'] ) ) {
			update_user_meta( $user_id, 'location', sanitize_text_field( wp_unslash( $_POST['location'] ) ) );
		}

		return true;
	}
}
