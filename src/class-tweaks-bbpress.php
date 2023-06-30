<?php
/**
 * These tweaks make sure that the real name of a user is never displayed.
 * Original source: http://www.rewweb.co.uk/bbpress-username-plugin/
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Registers bbPress overrides.
 */
class Tweaks_Bbpress {

	/**
	 * Register all filters for overriding default bbPress behaviour.
	 *
	 * @return void
	 */
	public function initialize() {
		add_filter( 'bbp_get_reply_author_display_name', array( $this, 'get_reply_author_display_name' ), 10, 2 );
		add_filter( 'bbp_get_topic_author_display_name', array( $this, 'get_topic_author_display_name' ), 10, 2 );
		add_filter( 'bbp_get_displayed_user_field', array( $this, 'get_displayed_user_field' ), 10, 2 );
		add_filter( 'bbp_get_user_profile_link', array( $this, 'get_user_profile_link' ), 10, 2 );
	}

	/**
	 * Return the author display_name of the reply
	 *
	 * @since 2.0.0 bbPress (r2667)
	 *
	 * @param string $author_name The display name of the author of the reply.
	 * @param int    $reply_id The reply id of the post.
	 * @return string The username of the author of the reply.
	 */
	public function get_reply_author_display_name( $author_name, $reply_id ) {
		$reply_id = bbp_get_reply_id( $reply_id );

		// User is not a guest.
		if ( ! bbp_is_reply_anonymous( $reply_id ) ) {

			// Get the author ID.
			$author_id = bbp_get_reply_author_id( $reply_id );

			// Get the user_login fallback name.
			$user_name = get_the_author_meta( 'user_login', $author_id );

			// User does not have an account.
		} else {
			$user_name = get_post_meta( $reply_id, '_bbp_anonymous_name', true );
		}

		// If nothing could be found anywhere, use Anonymous.
		if ( empty( $author_name ) ) {
			$user_name = __( 'Anonymous', 'iwar-theme' );
		}

		// Encode possible UTF8 display names.
		if ( seems_utf8( $user_name ) === false ) {
			$user_name = utf8_encode( $user_name );
		}

		return $user_name;
	}

	/**
	 * Return the author display_name of the topic
	 *
	 * @since 2.0.0 bbPress (r2485)
	 *
	 * @param string $author_name The display name of the author of the topic.
	 * @param int    $topic_id The topic id of the post.
	 * @return string The username of the author of the topic.
	 */
	public function get_topic_author_display_name( $author_name, $topic_id ) {
		$topic_id = bbp_get_topic_id( $topic_id );

		// Check for anonymous user.
		if ( ! bbp_is_topic_anonymous( $topic_id ) ) {

			// Get the author ID.
			$author_id = bbp_get_topic_author_id( $topic_id );

			// Get the user_login fallback name.
			$user_name = get_the_author_meta( 'user_login', $author_id );

			// User does not have an account.
		} else {
			$user_name = get_post_meta( $topic_id, '_bbp_anonymous_name', true );
		}

		// If nothing could be found anywhere, use Anonymous.
		if ( empty( $author_name ) ) {
			$user_name = __( 'Anonymous', 'iwar-theme' );
		}

		// Encode possible UTF8 display names.
		if ( seems_utf8( $user_name ) === false ) {
			$user_name = utf8_encode( $user_name );
		}

		return $user_name;
	}

	/**
	 * Return a sanitized user field value
	 *
	 * This function relies on the $filter parameter to decide how to sanitize
	 * the field value that it finds. Since it uses the WP_User object's magic
	 * __get() method, it can also be used to get user_meta values.
	 *
	 * @since 2.0.0 bbPress (r2688)
	 *
	 * @param string $value The value of the field.
	 * @param string $field Field to get.
	 * @see WP_User::__get() for more on how the value is retrieved
	 * @see sanitize_user_field() for more on how the value is sanitized
	 * @return string|bool Value of the field if it exists, else false
	 */
	public function get_displayed_user_field( $value, $field ) {

		// Override the full name field with the username.
		if ( 'display_name' === $field ) {
			// Get the displayed user.
			$user = bbpress()->displayed_user;

			// Get the username.
			$value = ucfirst( $user->user_login );
		}

		return $value;
	}

	/**
	 * Return link to the profile page of a user
	 *
	 * @since 2.0.0 bbPress (r2688)
	 *
	 * @param string $link The URL to the user profile as a full HTML tag.
	 * @param int    $user_id The user id.
	 * @return string The URL to the user profile as a full HTML tag.
	 */
	public function get_user_profile_link( $link, $user_id ) {

		// Validate user id.
		$user_id = bbp_get_user_id( $user_id );
		if ( empty( $user_id ) ) {
			return false;
		}

		// Get the user.
		$user = get_userdata( $user_id );
		if ( empty( $user ) ) {
			return false;
		}

		// user_login instead of display_name.
		$name = ! empty( $user->user_login )
			? $user->user_login
			: bbp_get_fallback_display_name();

		// URL.
		$url = bbp_get_user_profile_url( $user_id );

		// Link.
		$new_link = ! empty( $url )
			? '<a href="' . esc_url( $url ) . '">' . esc_html( $name ) . '</a>'
			: esc_html( $name );

		return $new_link;
	}
}
