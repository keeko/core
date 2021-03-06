<?php
namespace keeko\core\model;

use keeko\core\model\Base\Api as BaseApi;
use keeko\core\serializer\ApiSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_api' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Api extends BaseApi implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return ApiSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new ApiSerializer();
		}

		return self::$serializer;
	}
}
