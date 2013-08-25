<?php
namespace keeko\core\module;

interface ModuleInstallerInterface {
	
	public function install();
	
	public function uninstall();
	
	public function update($from, $to);
}