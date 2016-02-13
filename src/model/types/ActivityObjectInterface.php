<?php
namespace keeko\core\model\types;

use keeko\core\model\ActivityObject;

interface ActivityObjectInterface {

	/**
	 * Turns this object into an activity object
	 *
	 * @return ActivityObject
	 */
	public function toActivityObject();
}