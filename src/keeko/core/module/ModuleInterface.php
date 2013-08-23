<?php
namespace keeko\core\module;

use keeko\core\entities\Module;

interface ModuleInterface {

	public function setModule(Module $module);
	
	public function install();
	
	public function uninstall();
	
	public function update($from, $to);
}