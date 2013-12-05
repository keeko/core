<?php
namespace keeko\core;

use Composer\Package\Loader\JsonLoader;
use Composer\Package\Loader\ArrayLoader;
use keeko\core\exceptions\ModuleException;

class PackageManager {

	public function getModulePackage($packageName) {
		$path = KEEKO_PATH_MODULES . '/' . $packageName . '/composer.json';
		
		if (file_exists($path)) {
			$loader = new JsonLoader(new ArrayLoader());
			return $loader->load($path);
		} else {
			throw new ModuleException(sprintf('Module (%s) not found', $packageName));
		}
	}
}