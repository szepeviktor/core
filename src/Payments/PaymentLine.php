<?php
/**
 * Payment line
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use InvalidArgumentException;
use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\Money\TaxedMoney;
use Pronamic\WordPress\Pay\MoneyJsonTransformer;
use Pronamic\WordPress\Pay\TaxedMoneyJsonTransformer;

/**
 * Payment line.
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class PaymentLine {
	/**
	 * The ID.
	 *
	 * @var string|null
	 */
	private $id;

	/**
	 * The payment type.
	 *
	 * @see PaymentLineType
	 *
	 * @var string
	 */
	private $type;

	/**
	 * SKU.
	 *
	 * @var string|null
	 */
	private $sku;

	/**
	 * Name.
	 *
	 * @var string|null
	 */
	private $name;

	/**
	 * The description.
	 *
	 * @var string|null
	 */
	private $description;

	/**
	 * The quantity.
	 *
	 * @var int|null
	 */
	private $quantity;

	/**
	 * The unit price of this payment line.
	 *
	 * @var TaxedMoney|null
	 */
	private $unit_price;

	/**
	 * The discount amount of this payment line, no tax included.
	 *
	 * @var Money|null
	 */
	private $discount_amount;

	/**
	 * Total amount of this payment line.
	 *
	 * @var TaxedMoney
	 */
	private $total_amount;

	/**
	 * Product URL.
	 *
	 * @var string|null
	 */
	private $product_url;

	/**
	 * Image url.
	 *
	 * @var string|null
	 */
	private $image_url;

	/**
	 * Product category.
	 *
	 * @var string|null
	 */
	private $product_category;

	/**
	 * Payment line constructor.
	 */
	public function __construct() {
		$this->set_total_amount( new TaxedMoney() );
	}

	/**
	 * Get the id / identifier of this payment line.
	 *
	 * @return string|null
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the id / identifier of this payment line.
	 *
	 * @param string|null $id Number.
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Get type.
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Set type.
	 *
	 * @param string $type Type.
	 */
	public function set_type( $type ) {
		$this->type = $type;
	}

	/**
	 * Get the SKU of this payment line.
	 *
	 * @return string|null
	 */
	public function get_sku() {
		return $this->sku;
	}

	/**
	 * Set the SKU of this payment line.
	 *
	 * @param string|null $sku SKU.
	 */
	public function set_sku( $sku ) {
		$this->sku = $sku;
	}

	/**
	 * Get the name of this payment line.
	 *
	 * @return string|null
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the name of this payment line.
	 *
	 * @param string|null $name Name.
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Get the description of this payment line.
	 *
	 * @return string|null
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Set the description of this payment line.
	 *
	 * @param string|null $description Description.
	 */
	public function set_description( $description ) {
		$this->description = $description;
	}

	/**
	 * Get the quantity of this payment line.
	 *
	 * @return int
	 */
	public function get_quantity() {
		return $this->quantity;
	}

	/**
	 * Set the quantity of this payment line.
	 *
	 * @param int $quantity Quantity.
	 */
	public function set_quantity( $quantity ) {
		$this->quantity = $quantity;
	}

	/**
	 * Get unit price.
	 *
	 * @return TaxedMoney
	 */
	public function get_unit_price() {
		return $this->unit_price;
	}

	/**
	 * Set unit price.
	 *
	 * @param TaxedMoney $price Unit price.
	 */
	public function set_unit_price( TaxedMoney $price = null ) {
		$this->unit_price = ( null === $price ? null : $price );
	}

	/**
	 * Get discount amount, should not contain any tax.
	 *
	 * @return Money|null
	 */
	public function get_discount_amount() {
		return $this->discount_amount;
	}

	/**
	 * Set discount amount, should not contain any tax.
	 *
	 * @param Money $discount_amount Discount amount.
	 */
	public function set_discount_amount( Money $discount_amount = null ) {
		$this->discount_amount = $discount_amount;
	}

	/**
	 * Get tax amount.
	 *
	 * @return Money|null
	 */
	public function get_tax_amount() {
		return new Money(
			$this->get_total_amount()->get_tax_amount(),
			$this->get_total_amount()->get_currency()
		);
	}

	/**
	 * Get total amount.
	 *
	 * @return TaxedMoney
	 */
	public function get_total_amount() {
		return $this->total_amount;
	}

	/**
	 * Set total amount.
	 *
	 * @param TaxedMoney $total_amount Total amount.
	 */
	public function set_total_amount( TaxedMoney $total_amount ) {
		$this->total_amount = $total_amount;
	}

	/**
	 * Get product URL.
	 *
	 * @return string|null
	 */
	public function get_product_url() {
		return $this->product_url;
	}

	/**
	 * Set product URL.
	 *
	 * @param string|null $product_url Product URL.
	 */
	public function set_product_url( $product_url = null ) {
		$this->product_url = $product_url;
	}

	/**
	 * Get image URL.
	 *
	 * @return null|string
	 */
	public function get_image_url() {
		return $this->image_url;
	}

	/**
	 * Set image URL.
	 *
	 * @param null|string $image_url Image url.
	 */
	public function set_image_url( $image_url ) {
		$this->image_url = $image_url;
	}

	/**
	 * Get product category.
	 *
	 * @return null|string
	 */
	public function get_product_category() {
		return $this->product_category;
	}

	/**
	 * Set product category.
	 *
	 * @param null|string $product_category Product category.
	 */
	public function set_product_category( $product_category ) {
		$this->product_category = $product_category;
	}

	/**
	 * Create payment line from object.
	 *
	 * @param mixed $json JSON.
	 * @return PaymentLine
	 * @throws InvalidArgumentException Throws invalid argument exception when JSON is not an object.
	 */
	public static function from_json( $json ) {
		if ( ! is_object( $json ) ) {
			throw new InvalidArgumentException( 'JSON value must be an array.' );
		}

		$line = new self();

		if ( property_exists( $json, 'id' ) ) {
			$line->set_id( $json->id );
		}

		if ( property_exists( $json, 'type' ) ) {
			$line->set_type( $json->type );
		}

		if ( property_exists( $json, 'sku' ) ) {
			$line->set_sku( $json->sku );
		}

		if ( property_exists( $json, 'name' ) ) {
			$line->set_name( $json->name );
		}

		if ( property_exists( $json, 'description' ) ) {
			$line->set_description( $json->description );
		}

		if ( property_exists( $json, 'quantity' ) ) {
			$line->set_quantity( $json->quantity );
		}

		if ( isset( $json->unit_price ) ) {
			$line->set_unit_price( TaxedMoneyJsonTransformer::from_json( $json->unit_price ) );
		}

		if ( isset( $json->discount_amount ) ) {
			$line->set_discount_amount( MoneyJsonTransformer::from_json( $json->discount_amount ) );
		}

		if ( isset( $json->total_amount ) ) {
			$line->set_total_amount( TaxedMoneyJsonTransformer::from_json( $json->total_amount ) );
		}

		if ( property_exists( $json, 'product_url' ) ) {
			$line->set_product_url( $json->product_url );
		}

		if ( property_exists( $json, 'image_url' ) ) {
			$line->set_image_url( $json->image_url );
		}

		if ( property_exists( $json, 'product_category' ) ) {
			$line->set_product_category( $json->product_category );
		}

		return $line;
	}

	/**
	 * Get JSON.
	 *
	 * @return object
	 */
	public function get_json() {
		return (object) array(
			'id'               => $this->get_id(),
			'type'             => $this->get_type(),
			'sku'              => $this->get_sku(),
			'name'             => $this->get_name(),
			'description'      => $this->get_description(),
			'quantity'         => $this->get_quantity(),
			'unit_price'       => TaxedMoneyJsonTransformer::to_json( $this->get_unit_price() ),
			'discount_amount'  => MoneyJsonTransformer::to_json( $this->get_discount_amount() ),
			'total_amount'     => TaxedMoneyJsonTransformer::to_json( $this->get_total_amount() ),
			'product_url'      => $this->get_product_url(),
			'image_url'        => $this->get_image_url(),
			'product_category' => $this->get_product_category(),
		);
	}

	/**
	 * Create string representation of the payment line.
	 *
	 * @return string
	 */
	public function __toString() {
		$parts = array(
			$this->get_id(),
			$this->get_description(),
			$this->get_quantity(),
		);

		$parts = array_map( 'strval', $parts );

		$parts = array_map( 'trim', $parts );

		$parts = array_filter( $parts );

		$string = implode( ' - ', $parts );

		return $string;
	}
}