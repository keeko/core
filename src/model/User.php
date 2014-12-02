<?php

namespace keeko\core\model;

use keeko\core\model\Base\User as BaseUser;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser {

	public function newActivity(array $activity) {
		if (!isset($activity['verb']) && !isset($activity['object'])) {
			throw new \Exception(sprintf('missing verb and object for a new activity on user (%s)', $this->getDisplayName()));
		}
		$obj = new Activity();
		$obj->setActor($this);
		$obj->setVerb($activity['verb']);
		$obj->setObject($this->getActivityObject($activity['object']));

		if (isset($activity['target'])) {
			$obj->setTarget($this->getActivityObject($activity['target']));
		}
		
		$obj->save();
	}
	
	private function getActivityObject($data) {
		if (method_exists($data, 'toActivityObject')) {
			return $data->toActivityObject();
		}
	}
	
}
