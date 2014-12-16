<?php

namespace keeko\core\model;

use keeko\core\model\Base\User as BaseUser;
use keeko\core\utils\ActivityObjectInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
		$resolver = new OptionsResolver();
		$resolver->setRequired(['verb', 'object']);
		$resolver->setOptional(['target']);
		$resolver->setAllowedTypes([
			'target' => ['keeko\core\utils\ActivityObjectInterface', 'keeko\core\model\ActivityObject'],
			'object' => ['keeko\core\utils\ActivityObjectInterface', 'keeko\core\model\ActivityObject']
		]);
		$options = $resolver->resolve($activity);

		$obj = new Activity();
		$obj->setActor($this);
		$obj->setVerb($options['verb']);
		$obj->setObject($this->getActivityObject($options['object']));

		if (isset($options['target'])) {
			$obj->setTarget($this->getActivityObject($options['target']));
		}

		$obj->save();
	}

	private function getActivityObject($obj) {
		if ($obj instanceof ActivityObject) {
			return $obj;
		}
		
		if ($obj instanceof ActivityObjectInterface) {
			return $this->findActivityObject($obj->toActivityObject());
		}
	}

	private function findActivityObject(ActivityObject $ao) {
		$q = ActivityObjectQuery::create()
			->filterByClassName($ao->getClassName())
			->filterByType($ao->getType())
			->filterByReferenceId($ao->getId());

		$version = $ao->getVersion();
		if (!empty($version)) {
			$q = $q->filterByVersion($version);
		}

		$result = $q->findOne();

		if ($result) {
			$result->setDisplayName($ao->getDisplayName());
			return $result;
		}
		
		return $ao;
	}

}
