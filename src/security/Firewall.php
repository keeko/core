<?php
namespace keeko\core\security;

use keeko\core\service\ServiceContainer;
use keeko\core\model\Action;
use keeko\core\model\User;
use keeko\core\model\GroupQuery;

class Firewall {
	
	/** @var ServiceContainer */
	private $service;
	
	private $permissionTables = [];

	public function __construct(ServiceContainer $service) {
		$this->service = $service;
		$this->user = $service->getAuthManager()->getUser();
	}
	
	public function hasPermission($module, $action, User $user = null) {
		$module = $this->service->getModuleManager()->load($module);
		$action = $module->getActionModel($action);
		return $this->hasActionPermission($action, $user);
	}

	public function hasActionPermission(Action $action, User $user = null) {
		if ($user === null) {
			$user = $this->user;
		}
		$permissionTable = $this->getPermissionTable($user);
		
		return in_array($action->getId(), $permissionTable);
	}

	private function getPermissionTable(User $user) {
		$userId = $user->getId();
		if (isset($this->permissionTables[$userId])) {
			return $this->permissionTables[$userId];
		}
		
		// always allow what guests can do
		$guestGroup = GroupQuery::create()->findOneByIsGuest(true);
		
		// collect groups from user
		$groups = GroupQuery::create()->filterByUser($user)->find();
		$userGroup = GroupQuery::create()->filterByOwnerId(($userId))->findOne();
		if ($userGroup) {
			$groups[] = $userGroup;
		}
		$groups[] = $guestGroup;
		
		// ... structure them
		$this->permissionsTables[$userId] = [];
		foreach ($groups as $group) {
			foreach ($group->getActions() as $action) {
				$this->permissionsTables[$userId][] = $action->getId();
			}
		}
		
		return $this->permissionsTables[$userId];
	}
	
	private function isGuest(User $user) {
		return $user->getId() === -1;
	}
}