<?php
/**
 * Legacy Payments Data Store Custom Post Type
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\DateTime\DateTime;
use Pronamic\WordPress\DateTime\DateTimeZone;
use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\Pay\AbstractDataStoreCPT;
use Pronamic\WordPress\Pay\Address;
use Pronamic\WordPress\Pay\Customer;
use Pronamic\WordPress\Pay\ContactName;
use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Title: Payments data store CPT
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @see     https://woocommerce.com/2017/04/woocommerce-3-0-release/
 * @see     https://woocommerce.wordpress.com/2016/10/27/the-new-crud-classes-in-woocommerce-2-7/
 * @author  Remco Tolsma
 * @version 2.0.8
 * @since   3.7.0
 */
class LegacyPaymentsDataStoreCPT extends AbstractDataStoreCPT {
	/**
	 * Get contact name from legeacy meta.
	 *
	 * @param Payment $payment The payment to read.
	 * @return ContactName|null
	 */
	private function get_contact_name_from_legacy_meta( $payment ) {
		$id = $payment->get_id();

		$data = array(
			'first_name' => $this->get_meta( $id, 'first_name' ),
			'last_name'  => $this->get_meta( $id, 'last_name' ),
		);

		$data = array_map( 'trim', $data );
		$data = array_filter( $data );

		if ( empty( $data ) ) {
			// Bail out if there is no name data.
			return;
		}

		$name = new ContactName();

		if ( isset( $data['first_name'] ) ) {
			$name->set_first_name( $data['first_name'] );
		}

		if ( isset( $data['last_name'] ) ) {
			$name->set_last_name( $data['last_name'] );
		}

		return $name;
	}

	/**
	 * Maybe create customer from legeacy meta.
	 *
	 * @param Payment $payment The payment to read.
	 */
	private function maybe_create_customer_from_legacy_meta( $payment ) {
		if ( null !== $payment->get_customer() ) {
			// Bail out if there is already a customer.
			return;
		}

		$id = $payment->get_id();

		$data = array(
			'email'      => $this->get_meta( $id, 'email' ),
			'phone'      => $this->get_meta( $id, 'telephone_number' ),
			'ip_address' => $this->get_meta( $id, 'user_ip' ),
			'user_agent' => $this->get_meta( $id, 'user_agent' ),
			'language'   => $this->get_meta( $id, 'language' ),
			'locale'     => $this->get_meta( $id, 'locale' ),
		);

		$data = array_map( 'trim', $data );
		$data = array_filter( $data );

		if ( empty( $data ) ) {
			// Bail out if there is no customer data.
			return;
		}

		// Build customer from legacy meta data.
		$customer = new Customer();

		$payment->set_customer( $customer );

		// Customer name.
		$customer->set_name( $this->get_contact_name_from_legacy_meta( $payment ) );

		if ( isset( $data['email'] ) ) {
			$customer->set_email( $data['email'] );
		}

		if ( isset( $data['phone'] ) ) {
			$customer->set_phone( $data['phone'] );
		}

		if ( isset( $data['ip_address'] ) ) {
			$customer->set_ip_address( $data['ip_address'] );
		}

		if ( isset( $data['user_agent'] ) ) {
			$customer->set_user_agent( $data['user_agent'] );
		}

		if ( isset( $data['language'] ) ) {
			$customer->set_language( $data['language'] );
		}

		if ( isset( $data['locale'] ) ) {
			$customer->set_locale( $data['locale'] );
		}
	}

	/**
	 * Maybe create billing address from legeacy meta.
	 *
	 * @param Payment $payment The payment to read.
	 */
	private function maybe_create_billing_address_from_legacy_meta( $payment ) {
		if ( null !== $payment->get_billing_address() ) {
			// Bail out if there is already a billing address.
			return;
		}

		$id = $payment->get_id();

		$data = array(
			'line_1'      => $this->get_meta( $id, 'address' ),
			'postal_code' => $this->get_meta( $id, 'zip' ),
			'city'        => $this->get_meta( $id, 'city' ),
			'country'     => $this->get_meta( $id, 'country' ),
			'email'       => $this->get_meta( $id, 'email' ),
			'phone'       => $this->get_meta( $id, 'telephone_number' ),
		);

		$data = array_map( 'trim', $data );
		$data = array_filter( $data );

		if ( empty( $data ) ) {
			// Bail out if there is no address data.
			return;
		}

		$address = new Address();

		$payment->set_billing_address( $address );

		$address->set_name( $this->get_contact_name_from_legacy_meta( $payment ) );

		if ( isset( $data['line_1'] ) ) {
			$address->set_line_1( $data['line_1'] );
		}

		if ( isset( $data['postal_code'] ) ) {
			$address->set_postal_code( $data['postal_code'] );
		}

		if ( isset( $data['city'] ) ) {
			$address->set_city( $data['city'] );
		}

		if ( isset( $data['country'] ) ) {
			if ( 2 === strlen( $data['country'] ) ) {
				$address->set_country_code( $data['country'] );
			} else {
				$address->set_country_name( $data['country'] );
			}
		}

		if ( isset( $data['email'] ) ) {
			$address->set_email( $data['email'] );
		}

		if ( isset( $data['phone'] ) ) {
			$address->set_phone( $data['phone'] );
		}
	}

	/**
	 * Read post meta.
	 *
	 * @link https://github.com/woocommerce/woocommerce/blob/3.2.6/includes/abstracts/abstract-wc-data.php#L462-L507
	 * @param Payment $payment The payment to read.
	 */
	protected function read_post_meta( $payment ) {
		$this->maybe_create_customer_from_legacy_meta( $payment );
		$this->maybe_create_billing_address_from_legacy_meta( $payment );
	}

	/**
	 * Get update meta.
	 *
	 * @param Payment $payment The payment to update.
	 * @param array   $meta    Meta array
	 */
	protected function get_update_meta( $payment, $meta = array() ) {
		// Customer.
		$customer = $payment->get_customer();

		if ( null !== $customer ) {
			// Deprecated meta values.
			$meta['language']   = $customer->get_language();
			$meta['locale']     = $customer->get_locale();
			$meta['user_agent'] = $customer->get_user_agent();
			$meta['user_ip']    = $customer->get_ip_address();

			$name = $customer->get_name();

			if ( null !== $name ) {
				$meta['customer_name'] = (string) $name;
				$meta['first_name']    = $name->get_first_name();
				$meta['last_name']     = $name->get_last_name();
			}
		}

		$billing_address = $payment->get_billing_address();

		if ( null !== $billing_address ) {
			// Deprecated meta values.
			$meta['address']          = $billing_address->get_line_1();
			$meta['zip']              = $billing_address->get_postal_code();
			$meta['city']             = $billing_address->get_city();
			$meta['country']          = $billing_address->get_country_name();
			$meta['telephone_number'] = $billing_address->get_phone();
		}

		return $meta;
	}
}
