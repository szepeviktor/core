<?php
/**
 * Upgrade
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Upgrades
 */

namespace Pronamic\WordPress\Pay\Upgrades;

/**
 * Upgrade
 *
 * @author  Remco Tolsma
 * @version unreleased
 * @since   unreleased
 */
abstract class Upgrade {
	/**
	 * Version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Construct upgrade object.
	 *
	 * @param string $version Version.
	 */
	public function __construct( $version ) {
		$this->set_version( $version );
	}

	/**
	 * Get version.
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set version.
	 *
	 * @param string $version Version.
	 */
	public function set_version( $version ) {
		$this->version = $version;
	}

	/**
	 * Execute.
	 */
	abstract public function execute();
}