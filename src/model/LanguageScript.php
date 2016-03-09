<?php
namespace keeko\core\model;

use keeko\core\model\Base\LanguageScript as BaseLanguageScript;
use keeko\core\serializer\LanguageScriptSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_language_script' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class LanguageScript extends BaseLanguageScript implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new LanguageScriptSerializer();
		}

		return self::$serializer;
	}
}
