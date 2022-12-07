<?php
/**
 * Registers methods used for uninstalling the current instance of the library.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */

namespace StellarWP\Telemetry;

/**
 * Uninstall class used for uninstalling the current instance of the library.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Uninstall {

	/**
	 * Removes necessary items from the options table.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_slug The slug for the plugin being deleted.
	 *
	 * @return void
	 */
	public static function run( string $plugin_slug ) {
		require_once dirname( __FILE__ ) . '/Opt_In_Status.php';
		$opt_in_status = new Status();

		if ( $opt_in_status->plugin_exists( $plugin_slug ) ) {
			$opt_in_status->remove_plugin( $plugin_slug );
		}

		$optin_option_name = 'stellarwp_telemetry_' . $plugin_slug . '_show_optin';

		if ( get_option( $optin_option_name ) !== false ) {
			delete_option( $optin_option_name );
		}

		// If this is the last plugin in the optin option, let's remove the option entirely.
		self::maybe_remove_optin_option();
	}

	/**
	 * Removes the main telemetry option if the current plugin is the last one to use it.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function maybe_remove_optin_option() {
		$optin = get_option( 'stellarwp_telemetry' );

		// Bail if option has more than 'token' in the array.
		if ( count( $optin ) > 1 ) {
			return;
		}

		// All plugins have been removed, the token should be the only item in the array.
		if ( array_key_exists( 'token', $optin ) ) {
			delete_option( 'stellarwp_telemetry' );
			delete_option( 'stellarwp_telemetry_last_send' );
		}
	}
}
