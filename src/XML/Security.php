<?php

/**
 * Title: XML Security
 * Description:
 * Copyright: Copyright (c) 2005 - 2014
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Pronamic_WP_Pay_XML_Security {
	public static function filter( $variable, $filter = FILTER_SANITIZE_STRING ) {
		$result = null;

		if ( $variable ) {
			$result = filter_var( (string) $variable, $filter );
		}

		return $result;
	}
}