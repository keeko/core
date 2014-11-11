<?php

namespace keeko\core\model;

use keeko\core\model\Base\User as BaseUser;

class User extends BaseUser {

	/** @var Group[] */
	private $groups = [];
	
	/** @var Group */
	private $userGroup;
	
	private $permissionsTable = [];
	private $isGuest = false;
	
	/**
	 * Initializes internal state of User object.
	*/
	public function __construct($isGuest = false) {
		parent::__construct();
	}
	
// 	public function getGroups() {
// 		return $this->groups;
// 	}
	
	public function getUserGroup() {
		return $this->userGroup;
	}
	
	public function isGuest() {
		// 		return $this->isGuest;
	}
	
	private function readGroups() {
		if ($this->isGuest) {
			$this->groups = GroupQuery::create()->filterByIsGuest()->find();
		} else {
			$this->userGroup = GroupQuery::create()->filterByOwnerId(($this->getId()))->findOne();
			$this->groups = GroupQuery::create()->filterByUser($this)->find();
		}
	}
	
	private function readPermissions() {
		$groups = $this->groups;
		if ($this->userGroup) {
			$groups[] = $this->userGroup;
		}
	
		/* @var $group Group */
		foreach ($groups as $group) {
			foreach ($group->getActions() as $action) {
				$this->permissionsTable[$action->getModuleId()][$action->getId()] = $action;
				$this->permissionsTable[$action->getModuleId()][$action->getName()] = $action;
			}
		}
	}
	
	public function updatePermissions() {
		$this->readGroups();
		$this->readPermissions();
	}
	
	public function hasPermission($moduleId, $action) {
		return isset($this->permissionsTable[$moduleId])
				&& isset($this->permissionsTable[$moduleId][$action]);
	}
}
