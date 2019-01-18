<?php
/**
 * Abstract Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Common
 */

namespace Pronamic\WordPress\Pay\Gateways\Common;

/**
 * Title: Abstract Integration
 * Description:
 * Copyright: 2005-2019 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.1
 * @since   1.0.0
 * @link    https://github.com/thephpleague/omnipay-common/blob/master/src/Omnipay/Common/AbstractGateway.php
 */
abstract class AbstractIntegration implements IntegrationInterface {
	/**
	 * ID.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * URL.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Product URL.
	 *
	 * @var string
	 */
	public $product_url;

	/**
	 * Dashboard URL.
	 *
	 * @var string|array
	 */
	public $dashboard_url;

	/**
	 * Provider.
	 *
	 * @var string
	 */
	public $provider;

	/**
	 * Get ID.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set ID.
	 *
	 * @param string $id ID.
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set name.
	 *
	 * @param string $name Name.
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Get required settings for this integration.
	 *
	 * @link https://github.com/wp-premium/gravityforms/blob/1.9.16/includes/fields/class-gf-field-multiselect.php#L21-L42
	 * @return array
	 */
	public function get_settings() {
		return array();
	}

	/**
	 * Get dashboard URL.
	 *
	 * @return array
	 */
	public function get_dashboard_url() {
		$url = array();

		if ( isset( $this->dashboard_url ) ) {
			if ( is_string( $this->dashboard_url ) ) {
				$url = array( $this->dashboard_url );
			} elseif ( is_array( $this->dashboard_url ) ) {
				$url = $this->dashboard_url;
			}
		}

		return $url;
	}

	/**
	 * Get product URL.
	 *
	 * @return string|false
	 */
	public function get_product_url() {
		$url = false;

		if ( isset( $this->product_url ) ) {
			$url = $this->product_url;
		} elseif ( isset( $this->url ) ) {
			$url = $this->url;
		}

		return $url;
	}
}