<?php
/**
 * Gateway config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Core
 */

namespace Pronamic\WordPress\Pay\Core;

/**
 * Title: Gateway config
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.8
 * @since   1.0.0
 */
abstract class GatewayConfig {
	/**
	 * ID.
	 *
	 * @var int
	 */
	public $id;

	/**
	 * Post ID.
	 *
	 * @var int
	 */
	public $post_id;

	/**
	 * Mode
	 *
	 * @var string
	 */
	public $mode;

	/**
	 * Payment server URL.
	 *
	 * @todo Move to correct gateway integration library.
	 *
	 * @var string|null
	 */
	public $payment_server_url;

	/**
	 * URL.
	 *
	 * @todo Move to correct gateway integration library.
	 *
	 * @var string|null
	 */
	public $url;

	/**
	 * Certificates.
	 *
	 * @todo Move to correct gateway integration library.
	 *
	 * @var array|null
	 */
	public $certificates;

	/**
	 * Get gateway class.
	 *
	 * @return string
	 */
	public function get_gateway_class() {
		$class = get_class( $this );

		$namespace = substr( $class, 0, strrpos( $class, '\\' ) );

		return $namespace . '\Gateway';
	}
}
