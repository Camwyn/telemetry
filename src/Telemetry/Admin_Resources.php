<?php
/**
 * A collection of methods to enqueue and localize admin assets.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */

namespace StellarWP\Telemetry;

/**
 * A class that enqueues and localizes admin assets.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Admin_Resources {

	const SCRIPT_HANDLE  = 'stellarwp-telemetry-admin';
	const SCRIPT_VERSION = '1.0.0';

	/**
	 * Enqueues the admin resources.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_admin_assets() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'localize_script' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Enqueues the admin JS script.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$script_path = apply_filters( 'stellarwp/telemetry/' . Config::get_hook_prefix() . 'script_path', $this->get_asset_path() . 'resources/js/scripts.js' );

		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			$script_path,
			[ 'jquery' ],
			self::SCRIPT_VERSION,
			true
		);
	}

	/**
	 * Localizes the admin JS script.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localize_script() {
		$script_data = apply_filters( 'stellarwp/telemetry/' . Config::get_hook_prefix() . 'script_data', [
			'exit_interview' => [
				'action' => Exit_Interview_Subscriber::AJAX_ACTION,
				'nonce'  => wp_create_nonce( Exit_Interview_Subscriber::AJAX_ACTION ),
			],
		] );

		wp_localize_script(
			self::SCRIPT_HANDLE,
			'stellarwpTelemetry',
			$script_data
		);
	}

	/**
	 * Enqueues the admin CSS styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		$style_path = apply_filters( 'stellarwp/telemetry/' . Config::get_hook_prefix() . 'style_path', $this->get_asset_path() . 'resources/css/styles.css' );

		wp_enqueue_style(
			self::SCRIPT_HANDLE,
			$style_path,
			[],
			self::SCRIPT_VERSION
		);
	}

	/**
	 * Gets the path to the assets folder.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	static public function get_asset_path(): string {
		return plugin_dir_url( __DIR__ );
	}

}