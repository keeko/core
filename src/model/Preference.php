<?php
namespace keeko\core\model;

use keeko\core\model\Base\Preference as BasePreference;
use keeko\core\serializer\PreferenceSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_preference' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Preference extends BasePreference implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return PreferenceSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new PreferenceSerializer();
		}

		return self::$serializer;
	}
}
