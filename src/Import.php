<?php
/**
 * Extensions for the FG Joomla Import plugin.
 *
 * @package iwar-theme
 * @author Danny Schmarsel <ds@lichtmetzger.de>
 */

namespace IwarTheme;

use \PDO;

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/**
 * Registers additional import features.
 */
class Import {
	/**
	 * Initialization function.
	 *
	 * @return void
	 */
	public function initialize() {
		// Run after importing all users.
		add_action( 'fgj2wp_post_import_users', array( $this, 'import_special_user_data' ) );
	}

	/**
	 * THIS IS A HORRIBLE THING AND WE SHOULDN'T REALLY NEED TO DO THIS.
	 * WordPress checks if any files uploaded come from the global $_FILES array.
	 *
	 * Files only get added to this if they are uploaded via an HTTP form.
	 * Every upload (like wp_handle_upload, media_handle_sideload) has a hardcoded check for this which cannot be overriden.
	 *
	 * Temporarily disabling this check is easier than wrapping my file uploads into an HTTP POST request.
	 *
	 * wp-admin/includes/file.php:
	 * // A properly uploaded file will pass this test. There should be no reason to override this one.
	 * $test_uploaded_file = 'wp_handle_upload' === $action ? is_uploaded_file( $file['tmp_name'] ) : @is_readable( $file['tmp_name'] );
	 *
	 * @param  string $file_path A file inside the WordPress directory.
	 * @param  string $string The phrase currently in the file.
	 * @param  string $replacement The new phrase to replace $string.
	 * @return void
	 */
	private function replace_core_file_strings( $file_path, $string, $replacement ) {

		// Read the contents of the file.
		$file_contents = file_get_contents( ABSPATH . $file_path );

		// Perform the string replacement.
		$file_contents = str_replace(
			$string,
			$replacement,
			$file_contents
		);

		// Write the modified contents back to the file.
        // phpcs:ignore
		file_put_contents( $file_path, $file_contents );

	}


	/**
	 * THIS IS A HORRIBLE THING AND WE SHOULDN'T REALLY NEED TO DO THIS.
	 *
	 * @see replace_core_file_strings()
	 *
	 * Disables WordPress checking file uploads against $_GET.
	 *
	 * @return void
	 */
	public function disable_file_upload_security() {

		$this->replace_core_file_strings(
			'wp-admin/includes/file.php',
			'$test_uploaded_file = \'wp_handle_upload\' === $action ? is_uploaded_file( $file[\'tmp_name\'] ) : @is_readable( $file[\'tmp_name\'] );',
			'$test_uploaded_file = true;'
		);

	}

	/**
	 * THIS IS A HORRIBLE THING AND WE SHOULDN'T REALLY NEED TO DO THIS.
	 *
	 * @see replace_core_file_strings()
	 *
	 * Enables WordPress checking file uploads against $_GET.
	 *
	 * @return void
	 */
	public function enable_file_upload_security() {

		$this->replace_core_file_strings(
			'wp-admin/includes/file.php',
			'$test_uploaded_file = true;',
			'$test_uploaded_file = \'wp_handle_upload\' === $action ? is_uploaded_file( $file[\'tmp_name\'] ) : @is_readable( $file[\'tmp_name\'] );'
		);

	}

	/**
	 * Read out avatar URLs and the personal text from the kunena_users table in Joomla,
	 * download these avatar images into the media library and set
	 * the meta value of "basic_user_avatar" to the URL of the imported avatar.
	 *
	 * Also set the meta value of "personal_text" to the personal text.
	 *
	 * Avatars can then be used with the plugin "Basic User Avatars".
	 *
	 * @return void
	 */
	public function import_special_user_data() {

		$users   = get_users();
		$options = get_option( 'fgj2wp_options' );

		// Get Joomla database details.
		$hostname     = $options['hostname'];
		$database     = $options['database'];
		$username     = $options['username'];
		$password     = $options['password'];
		$table_prefix = $options['prefix'];

		// Establish a connection using PDO.
		try {
			$dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
			$pdo = new PDO( $dsn, $username, $password );
			$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			die( 'Database connection failed: ' . esc_html( $e->getMessage() ) );
		}

		// Disable WordPress file upload security checks.
		$this->disable_file_upload_security();

		foreach ( $users as $user ) {
			// Get the current user ID.
			$wp_user_id = $user->ID;

			// Get the old Joomla user ID.
			$j_user_id = get_user_meta( $wp_user_id, '_fgj2wp_old_user_id', true );

			// Grab the avatar URL and personal text from Joomla's database.
			$query = 'SELECT avatar,personalText,location FROM ' . $table_prefix . 'kunena_users WHERE userid=' . $j_user_id;
			$stmt  = $pdo->query( $query );

            // phpcs:ignore
			while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
				// Import personal text.
				if ( $row['personalText'] ) {
					$personal_text = $row['personalText'];

					// Save personal text into user information.
					update_user_meta( $wp_user_id, 'personal_text', $personal_text );
				}

				// Import location.
				if ( $row['location'] ) {
					$personal_text = $row['location'];

					// Save personal text into user information.
					update_user_meta( $wp_user_id, 'location', $personal_text );
				}

				// Import avatar.
				if ( $row['avatar'] ) {
					$avatar_url = 'https://i-war2.com/media/kunena/avatars/' . $row['avatar'];

					if ( ! function_exists( 'download_url' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
					}

					// Download the file to a temporary location.
					$tmp_file = download_url( $avatar_url );

					if ( ! is_wp_error( $tmp_file ) ) {
						// Get the file type.
						$file_info = wp_check_filetype( basename( $avatar_url ), null );
						// Get the original file name.
						$img_file_name = basename( $row['avatar'] );
						// Get the temporary file name.
						$tmp_file_name = basename( $tmp_file );
						// Get the upload directory.
						$upload_dir = wp_upload_dir();

						// Set up the file arguments.
						$file_args = array(
							'name'      => $img_file_name,
							'full_path' => $tmp_file,
							'type'      => $file_info['type'],
							// This is actually the full path, not just the name.
							'tmp_name'  => $tmp_file,
							'error'     => 0,
							'size'      => filesize( $tmp_file ),
						);

						if ( ! function_exists( 'media_handle_sideload' ) ) {
							require_once ABSPATH . 'wp-admin/includes/media.php';
						}

						if ( ! function_exists( 'wp_read_image_metadata' ) ) {
							require_once ABSPATH . 'wp-admin/includes/image.php';
						}

						// Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload".
						$uploaded_file = media_handle_sideload( $file_args );

						if ( is_int( $uploaded_file ) ) {
							// Save avatar into user information.
							update_user_meta( $wp_user_id, 'basic_user_avatar', array( 'full' => wp_get_attachment_url( $uploaded_file ) ) );
						} else {
							echo 'Avatar file upload failed.';
						}
					}
				}
			}
		}

		// Enable WordPress file upload security checks.
		$this->disable_file_upload_security();
	}
}
