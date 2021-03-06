<?php
/**
 * Prorating Rule
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Privacy
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use DateTimeImmutable;

/**
 * Prorating Rule
 *
 * @author  Remco Tolsma
 * @version unreleased
 * @since   unreleased
 */
class ProratingRule {
	/**
	 * Weekdays indexed 0 (for Sunday) through 6 (for Saturday).
	 *
	 * @var array<int, string>
	 */
	private static $weekdays = array(
		0 => 'Sunday',
		1 => 'Monday',
		2 => 'Tuesday',
		3 => 'Wednesday',
		4 => 'Thursday',
		5 => 'Friday',
		6 => 'Saturday',
	);

	/**
	 * Frequency.
	 *
	 * @var string
	 */
	private $frequency;

	/**
	 * Boolean flag to allow month overflow.
	 *
	 * @link https://carbon.nesbot.com/docs/#overflow-static-helpers
	 * @var bool
	 */
	private $month_overflow = false;

	/**
	 * Day of the week.
	 *
	 * @var int
	 */
	private $by_day_of_the_week;

	/**
	 * Day of the month.
	 *
	 * @var int
	 */
	private $by_day_of_the_month;

	/**
	 * Number of month.
	 *
	 * @var int
	 */
	private $by_month;

	/**
	 * Construct prorating rule.
	 *
	 * @param string $frequency Frequency.
	 */
	public function __construct( $frequency ) {
		$this->frequency = $frequency;
	}

	/**
	 * By numeric day of the week.
	 *
	 * @param int $number Number of day in the week (0 = Sunday).
	 *
	 * @return $this
	 */
	public function by_numeric_day_of_the_week( $number ) {
		$this->by_day_of_the_week = self::$weekdays[ $number ];

		return $this;
	}

	/**
	 * By numeric day of the month.
	 *
	 * @param int $number Day of the month.
	 * @return $this
	 */
	public function by_numeric_day_of_the_month( $number ) {
		$this->by_day_of_the_month = $number;

		return $this;
	}

	/**
	 * By numeric month.
	 *
	 * @param int $number Number of month.
	 * @return $this
	 */
	public function by_numeric_month( $number ) {
		$this->by_month = $number;

		return $this;
	}

	/**
	 * Get date.
	 *
	 * @param DateTimeImmutable|null $date Date.
	 * @return DateTimeImmutable|false
	 * @throws \Exception Throws exception on date error.
	 */
	public function get_date( DateTimeImmutable $date = null ) {
		if ( null === $date ) {
			$date = new DateTimeImmutable();
		}

		return $this->apply_properties( $date );
	}

	/**
	 * Apply properties.
	 *
	 * @param DateTimeImmutable $date Date.
	 * @return DateTimeImmutable|false
	 */
	private function apply_properties( DateTimeImmutable $date ) {
		$year  = $date->format( 'Y' );
		$month = $date->format( 'm' );
		$day   = $date->format( 'd' );

		// 1 > null === true
		if ( $day >= $this->by_day_of_the_month && 'W' !== $this->frequency ) {
			$month++;
		}

		if ( null !== $this->by_day_of_the_month ) {
			$day = $this->by_day_of_the_month;
		}

		if ( false === $this->month_overflow ) {
			$date = $date->setDate( $year, $month, 1 );

			$days_in_month = $date->format( 't' );

			if ( $day > $days_in_month ) {
				$day = $days_in_month;
			}
		}

		if ( null !== $this->by_month ) {
			if ( $month > $this->by_month ) {
				$year++;
			}

			$month = $this->by_month;
		}

		$date = $date->setDate( $year, $month, $day );

		// Day of the week.
		$day_of_the_week = $this->by_day_of_the_week;

		if ( null === $day_of_the_week && 'W' === $this->frequency ) {
			$day_of_the_week = $date->format( 'l' );
		}

		if ( null !== $day_of_the_week ) {
			$date = $date->modify( 'Next ' . $day_of_the_week );
		}

		return $date;
	}
}
