<?php
namespace keeko\core\model;

use keeko\core\model\Base\RegionType as BaseRegionType;
use keeko\core\serializer\RegionTypeSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_region_type' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class RegionType extends BaseRegionType implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new RegionTypeSerializer();
		}

		return self::$serializer;
	}
}
