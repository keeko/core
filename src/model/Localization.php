<?php
namespace keeko\core\model;

use keeko\core\model\Base\Localization as BaseLocalization;
use keeko\core\serializer\LocalizationSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_localization' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Localization extends BaseLocalization implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return LocalizationSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new LocalizationSerializer();
		}

		return self::$serializer;
	}
}
