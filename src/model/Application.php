<?php
namespace keeko\core\model;

use keeko\core\model\Base\Application as BaseApplication;
use keeko\core\model\serializer\ApplicationSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_application' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Application extends BaseApplication implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new ApplicationSerializer();
		}

		return self::$serializer;
	}
}
