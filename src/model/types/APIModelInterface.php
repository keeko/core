<?php
namespace keeko\core\model\types;

interface APIModelInterface {
	
	/**
	 * Returns the type name used for this model in the public API
	 *
	 * @return ModelSerializerInterface
	 */
	public static function getSerializer();
}