<?php
namespace keeko\core\model;

use keeko\core\model\Base\RegionArea as BaseRegionArea;
use keeko\core\serializer\RegionAreaSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_region_area' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class RegionArea extends BaseRegionArea implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return RegionAreaSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new RegionAreaSerializer();
		}

		return self::$serializer;
	}
}
