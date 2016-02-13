<?php

namespace keeko\core\model;

use keeko\core\model\Base\Group as BaseGroup;
use keeko\core\model\types\APIModelInterface;
use keeko\core\model\serializer\GroupSerializer;

/**
 * Skeleton subclass for representing a row from the 'kk_group' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Group extends BaseGroup implements APIModelInterface {

	private static $serializer = null;
	
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new GroupSerializer();
		}
		
		return self::$serializer;
	}

}
