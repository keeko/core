<?php
namespace keeko\core\model;

use keeko\core\model\Base\Currency as BaseCurrency;
use keeko\core\model\serializer\CurrencySerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_currency' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Currency extends BaseCurrency implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new CurrencySerializer();
		}

		return self::$serializer;
	}
}
