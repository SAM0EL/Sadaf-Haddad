<?php
/**
 * Sort array values by key, default key is 'weight'
 * Used in uasort() function.
 * For fix equal weight problem used $this->data array_search
 *
 * @since 4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Class Vc_Sort
 *
 * @since 4.4
 */
class Vc_Sort {
	/**
	 * Array of data to be sorted.
	 *
	 * @since 4.4
	 * @var array $data - sorting data
	 */
	protected $data = [];
	/**
	 * Key used for sorting.
	 *
	 * @since 4.4
	 * @var string $key - key for search
	 */
	protected $key = 'weight';
	/**
	 * VC_Sort constructor.
	 *
	 * @param array $data - array to sort.
	 * @since 4.4
	 */
	public function __construct( $data ) {
		$this->data = $data;
	}

	/**
	 * Used to change/set data to sort
	 *
	 * @param array $data
	 * @since 4.5
	 */
	public function setData( $data ) {
		$this->data = $data;
	}

	/**
	 * Sort $this->data by user key, used in class-vc-mapper.
	 * If keys are equals it SAVES a position in array (index).
	 *
	 * @param string $key
	 *
	 * @return array - sorted array
	 * @since 4.4
	 */
	public function sortByKey( $key = 'weight' ) {
		$this->key = $key;
		uasort( $this->data, [
			$this,
			'key',
		] );

		return array_merge( $this->data ); // reset array keys to 0..N.
	}

	/**
	 * Sorting by key callable for usort function
	 *
	 * @param array $a - compare value.
	 * @param array $b - compare value.
	 *
	 * @return int
	 * @since 4.4
	 */
	private function key( $a, $b ) {
		$a_weight = isset( $a[ $this->key ] ) ? (int) $a[ $this->key ] : 0;
		$b_weight = isset( $b[ $this->key ] ) ? (int) $b[ $this->key ] : 0;
		// To save real-ordering.
		if ( $a_weight === $b_weight ) {
			// @codingStandardsIgnoreLine
			$cmp_a = array_search( $a, $this->data );
			// @codingStandardsIgnoreLine
			$cmp_b = array_search( $b, $this->data );

			return $cmp_a - $cmp_b;
		}

		return $b_weight - $a_weight;
	}

	/**
	 * Get the currently sorted data.
	 *
	 * @return array - sorting data
	 * @since 4.4
	 */
	public function getData() {
		return $this->data;
	}
}
