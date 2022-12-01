<?php
/**
 * Handles all hooks/filters related to the admin screens.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
namespace StellarWP\Telemetry;

use StellarWP\Telemetry\Contracts\Abstract_Subscriber;

/**
 * Handles all hooks/filters related to the admin screens.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Admin_Subscriber extends Abstract_Subscriber {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', [ $this, 'maybe_enqueue_admin_assets' ] );

	}

	/**
	 * Registers required hooks to set up the admin assets.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function maybe_enqueue_admin_assets() {
		global $pagenow;

		if ( $pagenow === 'plugins.php' || $this->container->get( Opt_In_Template::class )->should_render() ) {
			$this->container->get( Admin_Resources::class )->enqueue_admin_assets();
		}
	}

}