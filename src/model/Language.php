<?php
namespace keeko\core\model;

use keeko\core\model\Base\Language as BaseLanguage;
use keeko\core\serializer\LanguageSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_language' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Language extends BaseLanguage implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return LanguageSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new LanguageSerializer();
		}

		return self::$serializer;
	}
}
