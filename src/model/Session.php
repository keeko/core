<?php
namespace keeko\core\model;

use keeko\core\model\Base\Session as BaseSession;
use keeko\core\serializer\SessionSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_session' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Session extends BaseSession implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return SessionSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new SessionSerializer();
		}

		return self::$serializer;
	}
}
