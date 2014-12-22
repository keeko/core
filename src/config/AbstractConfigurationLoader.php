<?php
namespace keeko\core\config;

use Symfony\Component\Config\Loader\FileLoader;

abstract class AbstractConfigurationLoader extends FileLoader {

	protected $loaded = false;
	
	public function isLoaded() {
		return $this->loaded;
	}

}