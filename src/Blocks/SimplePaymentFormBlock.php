<?php
/**
 * Simple payment form block.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay\Blocks;

use Pronamic\WordPress\Money\Parser;

/**
 * DonationBlock.php
 *
 * @author  Reüel van der Steege
 * @since   2.1.7
 * @version 2.1.7
 */
class SimplePaymentFormBlock {
	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function __construct() {
		$min = SCRIPT_DEBUG ? '' : '.min';

		// Register editor script.
		wp_register_script(
			'pronamic-simple-payment-form-editor',
			plugins_url( '/js/block-payment-button' . $min . '.js', pronamic_pay_plugin()->get_file() ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-element' ),
			pronamic_pay_plugin()->get_version()
		);

		// Localize script.
		wp_localize_script(
			'pronamic-simple-payment-form-editor',
			'pronamic_simple_payment_form',
			array(
				'title'        => __( 'Simple Payment Form', 'pronamic_ideal' ),
				'label_amount' => __( 'Amount', 'pronamic_ideal' ),
			)
		);

		// Return block registration arguments.
		register_block_type(
			'pronamic-pay/simple-payment-form',
			array(
				'render_callback' => array( $this, 'render_block' ),
				'editor_script' => 'pronamic-simple-payment-form-editor',
				'attributes'    => array(
					'amount' => array(
						'type'    => 'string',
						'default' => '0',
					),
				),
			)
		);
	}

	/**
	 * Render block.
	 *
	 * @param array $attributes Attributes.
	 *
	 * @return string|null
	 */
	public function render_block( $attributes = array() ) {
		ob_start();

		$money_parser = new Parser();

		$settings = array(
			'amount' => $money_parser->parse( $attributes['amount'] )->get_cents(),
		);

		echo pronamic_pay_plugin()->forms_module->get_form_output( $settings ); // WPCS: XSS ok.

		$html = ob_get_contents();

		ob_end_clean();

		return $html;
	}
}