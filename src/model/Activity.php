<?php
namespace keeko\core\model;

use keeko\core\model\Base\Activity as BaseActivity;
use keeko\core\serializer\ActivitySerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_activity' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Activity extends BaseActivity implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new ActivitySerializer();
		}

		return self::$serializer;
	}
}
