<?php
namespace keeko\core\module;

use keeko\core\entities\Module;

abstract class AbstractModule implements ModuleInterface {

	/* @var $module Module */
	protected $module;
	
	/* (non-PHPdoc)
	 * @see \keeko\core\module\ModuleInterface::setModule()
	 */
	public function setModule(Module $module) {
		$this->module = $module;
	}

}
