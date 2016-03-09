<?php
namespace keeko\core\model;

use keeko\core\model\Base\LanguageType as BaseLanguageType;
use keeko\core\serializer\LanguageTypeSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_language_type' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class LanguageType extends BaseLanguageType implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new LanguageTypeSerializer();
		}

		return self::$serializer;
	}
}
