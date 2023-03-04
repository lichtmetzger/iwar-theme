<?php

/*
* These tweaks make sure that the real name of a user is never displayed.
* Original source: http://www.rewweb.co.uk/bbpress-username-plugin/
* TODO: Make sure all of this still works, since the plugin is very old.
*/

//This function is a copy of bbp_get_reply_author_display_name, but with commented text removed
function rew_get_reply_author_display_name( $author_id, $reply_id) {
		$reply_id = bbp_get_reply_id( $reply_id );

		// User is not a guest
		if ( !bbp_is_reply_anonymous( $reply_id ) ) {

			// Get the author ID
			$author_id = bbp_get_reply_author_id( $reply_id );
			//////////  TEXT REMOVED FROM ORIGINAL FUNCTION 
			/* Try to get a display name
			$author_name = get_the_author_meta( 'display_name', $author_id );

			// Fall back to user login
			if ( empty( $author_name ) ) {
				$author_name = get_the_author_meta( 'user_login', $author_id );
			}
			*/
			$author_name = get_the_author_meta( 'user_login', $author_id );
			

		// User does not have an account
		} else {
			$author_name = get_post_meta( $reply_id, '_bbp_anonymous_name', true );
		}

		// If nothing could be found anywhere, use Anonymous
		if ( empty( $author_name ) )
			$author_name = __( 'Anonymous', 'bbpress' );

		// Encode possible UTF8 display names
		if ( seems_utf8( $author_name ) === false )
			$author_name = utf8_encode( $author_name );
		
		return $author_name ;
	}

add_filter( 'bbp_get_reply_author_display_name', 'rew_get_reply_author_display_name', 10, 2) ;



//This function is a copy of bbp_get_topic_author_display_name, but with commented text removed
function rew_get_topic_author_display_name( $author_id, $topic_id ) {
		$topic_id = bbp_get_topic_id( $topic_id );

		// Check for anonymous user
		if ( !bbp_is_topic_anonymous( $topic_id ) ) {

			// Get the author ID
			$author_id = bbp_get_topic_author_id( $topic_id );
			//////////  TEXT REMOVED FROM ORIGINAL FUNCTION 
			/*Try to get a display name
			$author_name = get_the_author_meta( 'display_name', $author_id );

			// Fall back to user login
			if ( empty( $author_name ) ) {
				$author_name = get_the_author_meta( 'user_login', $author_id );
			}
			*/
			$author_name = get_the_author_meta( 'user_login', $author_id );
			
			
		// User does not have an account
		} else {
			$author_name = get_post_meta( $topic_id, '_bbp_anonymous_name', true );
		}

		// If nothing could be found anywhere, use Anonymous
		if ( empty( $author_name ) )
			$author_name = __( 'Anonymous', 'bbpress' );

		// Encode possible UTF8 display names
		if ( seems_utf8( $author_name ) === false )
			$author_name = utf8_encode( $author_name );
		
		return $author_name ; 
		}

add_filter( 'bbp_get_topic_author_display_name', 'rew_get_topic_author_display_name', 10, 2);

//amend in profile display
function rew_get_displayed_user_field( $field = '', $filter = 'display' ) {

		// Get the displayed user
		$user         = bbpress()->displayed_user;
		$author_name  = ucfirst($user->user_login);
		return $author_name;

		// Juggle the user filter property because we don't want to muck up how
		// other code might interact with this object.
		$old_filter   = $user->filter;
		$user->filter = $filter;

		// Get the field value from the WP_User object. We don't need to perform
		// an isset() because the WP_User::__get() does it for us.
		$value        = $user->$field;

		// Put back the user filter property that was previously juggled above.
		$user->filter = $old_filter;

		// Return empty
		return $value ;
		//return apply_filters( 'bbp_get_displayed_user_field', $value, $field, $filter );
	}


add_filter( 'bbp_get_displayed_user_field', 'rew_get_displayed_user_field');



function replace_bbpress_profile_link_text_filter($user_link, $user_id){
		$author_id = $user_id ;
		$author_object = get_userdata( $author_id );
		$user_name  = ucfirst($author_object->user_login);
		if ( empty( $user_id ) )
			return false;

		$user      = get_userdata( $user_id );
		$name      = esc_attr( $user_name );
		$user_link = '<a href="' . bbp_get_user_profile_url( $user_id ) . '" title="' . $name . '">' . $name . '</a>';
}
add_filter( 'bbp_get_user_profile_link', 'replace_bbpress_profile_link_text_filter', 10,2) ;