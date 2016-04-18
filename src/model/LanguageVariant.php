<?php
namespace keeko\core\model;

use keeko\core\model\Base\LanguageVariant as BaseLanguageVariant;
use keeko\core\serializer\LanguageVariantSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_language_variant' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class LanguageVariant extends BaseLanguageVariant implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return LanguageVariantSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new LanguageVariantSerializer();
		}

		return self::$serializer;
	}
}
