<?php
/**
 * Dependencies
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Dependencies
 */

namespace Pronamic\WordPress\Pay\Dependencies;

/**
 * Dependencies
 *
 * @author  Remco Tolsma
 * @version unreleased
 * @since   unreleased
 */
class Dependencies {
	/**
	 * Dependencies.
	 *
	 * @var array<Dependency>
	 */
	private $dependencies;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->dependencies = array();
	}

	/**
	 * Add dependency.
	 *
	 * @param Dependency $dependency The dependency to add.
	 */
	public function add( Dependency $dependency ) {
		$this->dependencies[] = $dependency;
	}

	/**
	 * Are met.
	 *
	 * @return bool True if dependencies are met, false otherwise.
	 */
	public function are_met() {
		foreach ( $this->dependencies as $dependency ) {
			if ( ! $dependency->is_met() ) {
				return false;
			}
		}

		return true;
	}
}
