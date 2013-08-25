<?php
namespace keeko\core\module;

use keeko\core\entities\Module;
use keeko\core\application\Keeko;
use keeko\core\action\ControllerInterface;
use keeko\core\action\ActionInterface;

interface ModuleInterface {
	
	public function setKeeko(Keeko $application);
	
	/**
	 * Returns the Keeko Application
	 * 
	 * @return Keeko
	 */
	public function getKeeko();

	public function setEntity(Module $module);
	
	/**
	 * Returns the entity
	 * 
	 * @return Module
	 */
	public function getEntity();
	
	/**
	 * 
	 * @param unknown $name
	 * @return ActionInterface
	 */
	public function loadAction($name);
	
	/**
	 * 
	 * @param unknown $name
	 * @return ControllerInterface
	 */
	public function loadController($name);
}