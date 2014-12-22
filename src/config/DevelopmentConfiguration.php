<?php
namespace keeko\core\config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use keeko\core\config\definition\DevelopmentDefinition;

class DevelopmentConfiguration extends AbstractConfigurationLoader {

	private $config;
	
	public function load($resource, $type = null) {
		if (file_exists($resource)) {
			$this->loaded = true;
			$config = Yaml::parse($resource);
			$processor = new Processor();
			$this->config = $processor->processConfiguration(new DevelopmentDefinition(), $config);
		}
	}
	
	public function supports($resource, $type = null) {
		return pathinfo($resource, PATHINFO_EXTENSION) === 'yaml' && pathinfo($resource, PATHINFO_FILENAME) === 'development';
	}
	
	public function getPropelLogging() {
		return $this->config['propel']['logging'];
	}
}