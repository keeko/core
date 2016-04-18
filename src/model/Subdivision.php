<?php
namespace keeko\core\model;

use keeko\core\model\Base\Subdivision as BaseSubdivision;
use keeko\core\serializer\SubdivisionSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_subdivision' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Subdivision extends BaseSubdivision implements ApiModelInterface {

	/**
	 */
	private static $serializer;

	/**
	 * @return SubdivisionSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new SubdivisionSerializer();
		}

		return self::$serializer;
	}
}
