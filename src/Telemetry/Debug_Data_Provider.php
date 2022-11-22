<?php
/**
 * A helper class for getting site health data.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
namespace StellarWP\Telemetry;

use StellarWP\Telemetry\Contracts\Data_Provider;
use WP_Debug_Data;

/**
 * Provides methods for retrieving site health data.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Debug_Data_Provider implements Data_Provider {

	/**
	 * Gets the current site health data
	 *
	 * @since 1.0.0
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_debug_data/
	 *
	 * @return array Site health data
	 */
	public function get_data(): array {
		if ( ! class_exists( 'WP_Debug_Data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
		}
		$info = WP_Debug_Data::debug_data();

		$active_plugins = get_option('active_plugins');
		$plugins        = get_plugins();
		$active         = [];

		foreach ( $active_plugins as $active_plugin ){
			if ( isset( $plugins[ $active_plugin ] ) ) {
				$active[ $active_plugin ] = $plugins[ $active_plugin ];
				unset( $plugins[ $active_plugin ] );
			}
		}
		$info['telemetry-active-plugins']['fields'] = $active;
		$info['telemetry-inactive-plugins']['fields'] = $plugins;

		/**
		 * Filters the site health data array.
		 *
		 * @since 1.0.0
		 *
		 * @see https://developer.wordpress.org/reference/classes/wp_debug_data/debug_data/
		 *
		 * @param array $info The array of site data.
		 */
		return apply_filters( 'stellarwp/telemetry/' . Config::get_hook_prefix() . 'data', $info );
	}
}
