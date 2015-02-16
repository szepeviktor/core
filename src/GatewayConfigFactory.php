<?php

/**
 * Title: Gateway config factory
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
abstract class Pronamic_WP_Pay_GatewayConfigFactory {
	public abstract function get_config( $post_id );
}
