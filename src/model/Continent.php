<?php
namespace keeko\core\model;

use keeko\core\model\Base\Continent as BaseContinent;
use keeko\core\serializer\ContinentSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_continent' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Continent extends BaseContinent implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return ContinentSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new ContinentSerializer();
		}

		return self::$serializer;
	}
}
