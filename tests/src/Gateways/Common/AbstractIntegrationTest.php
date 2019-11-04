<?php
/**
 * Abstract integration test
 *
 * @author    Reüel van der Steege
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\Common
 */

namespace Pronamic\WordPress\Pay\Gateways\Common;

/**
 * Abstract integration test
 *
 * @author  Reüel van der Steege
 * @version 1.0
 * @since   2.2.6
 */
class AbstractIntegrationTest extends \WP_UnitTestCase {
	/**
	 * Integration mock.
	 *
	 * @var AbstractIntegration
	 */
	private $integration;

	/**
	 * Setup.
	 */
	public function setUp() {
		// Abstract integration class mock.
		$this->integration = $this->getMockForAbstractClass( 'Pronamic\WordPress\Pay\Gateways\Common\AbstractIntegration' );

		$this->integration->set_manual_url( 'https://domain.tld/manuals/test/' );
	}

	/**
	 * Test manual URL.
	 */
	public function test_get_manual_url() {
		$expected = 'https://domain.tld/manuals/test/';

		$this->assertEquals( $expected, $this->integration->get_manual_url() );
	}
}