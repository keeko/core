<?php
namespace keeko\core\config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use keeko\core\config\definition\DevelopmentDefinition;
use keeko\core\config\definition\GeneralDefinition;

class GeneralConfiguration extends AbstractConfigurationLoader {

	private $config;
	
	public function load($resource, $type = null) {
		if (file_exists($resource)) {
			$this->loaded = true;
			$config = Yaml::parse($resource);
			$processor = new Processor();
			$this->config = $processor->processConfiguration(new GeneralDefinition(), $config);
		}
	}
	
	public function supports($resource, $type = null) {
		return pathinfo($resource, PATHINFO_EXTENSION) === 'yaml' && pathinfo($resource, PATHINFO_FILENAME) === 'general';
	}
	
	public function getPathsFiles() {
		return $this->config['paths']['files'];
	}
}