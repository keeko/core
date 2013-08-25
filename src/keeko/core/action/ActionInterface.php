<?php
namespace keeko\core\action;

use keeko\core\entities\Action;
use keeko\core\module\ModuleInterface;

interface ActionInterface {
	public function getData();
	
	public function setEntity(Action $action);
	
	public function setModule(ModuleInterface $module);
	
	public function setParams($params);
}