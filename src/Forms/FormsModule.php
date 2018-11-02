<?php
/**
 * Forms Module
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Forms
 */

namespace Pronamic\WordPress\Pay\Forms;

use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Plugin;

/**
 * Forms Module
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class FormsModule {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initalize a forms module object.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Form Post Type.
		$this->form_post_type = new FormPostType( $plugin );

		// Processor.
		$this->processor = new FormProcessor( $plugin );

		// Scripts.
		$this->scripts = new FormScripts( $plugin );

		// Shortcode.
		$this->shortcode = new FormShortcode( $this );

		// Actions.
		add_filter( 'the_content', array( $this, 'maybe_add_form_to_content' ) );

		add_filter( 'pronamic_payment_source_text_payment_form', array( $this, 'source_text' ), 10, 2 );
		add_filter( 'pronamic_payment_source_description_payment_form', array( $this, 'source_description' ), 10, 2 );
	}

	/**
	 * Maybe add form to content.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/the_content/
	 * @param string $content Post content to maybe extend with a payment form.
	 * @return string
	 */
	public function maybe_add_form_to_content( $content ) {
		if ( is_singular( 'pronamic_pay_form' ) && 'pronamic_pay_form' === get_post_type() ) {
			$content .= $this->get_form_output( get_the_ID() );
		}

		return $content;
	}

	/**
	 * Get form output.
	 *
	 * @param string|array $id Form ID or form settings.
	 * @return string
	 */
	public function get_form_output( $id ) {
		if ( is_array( $id ) ) {
			$config_id     = $id['config_id'];
			$button_text   = null;
			$amount_method = FormPostType::AMOUNT_METHOD_INPUT_FIXED;
			$amounts       = array( $id['amount'] );
			$title         = null;

			$id = 'button-' . base64_encode(
				wp_json_encode(
					(object) array(
						'config_id' => $config_id,
					)
				)
			);
		} else {
			$config_id     = get_post_meta( $id, '_pronamic_payment_form_config_id', true );
			$button_text   = get_post_meta( $id, '_pronamic_payment_form_button_text', true );
			$amount_method = get_post_meta( $id, '_pronamic_payment_form_amount_method', true );
			$amounts       = get_post_meta( $id, '_pronamic_payment_form_amount_choices', true );
			$title         = ( is_singular( 'pronamic_pay_form' ) ? null : get_the_title( $id ) );
		}

		// Button text.
		if ( empty( $button_text ) ) {
			$button_text = empty( $button_text ) ? __( 'Pay Now', 'pronamic_ideal' ) : $button_text;
		}

		// Load template.
		$file = plugin_dir_path( $this->plugin->get_file() ) . 'templates/form.php';

		ob_start();

		include $file;

		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Source text filter.
	 *
	 * @param string  $text    The source text to filter.
	 * @param Payment $payment The payment for the specified source text.
	 * @return string
	 */
	public function source_text( $text, Payment $payment ) {
		$text = __( 'Payment Form', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>',
			get_edit_post_link( $payment->source_id ),
			$payment->source_id
		);

		return $text;
	}

	/**
	 * Source description filter.
	 *
	 * @param string  $text    The source text to filter.
	 * @param Payment $payment The payment for the specified source text.
	 * @return string
	 */
	public function source_description( $text, Payment $payment ) {
		$text = __( 'Payment Form', 'pronamic_ideal' ) . '<br />';

		return $text;
	}
}
