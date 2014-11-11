<?php
namespace keeko\core\action;

use keeko\core\module\AbstractModule;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractResponse {

	protected $data;

	protected $twig;

	protected $module;

	public function __construct(AbstractModule $module, $format) {
		$this->module = $module;
		$templatePath = sprintf('%s/%s/templates/%s', KEEKO_PATH_MODULES, $module->getModel()->getName(), $format);
		
		if (file_exists($templatePath)) {
			$loader = new \Twig_Loader_Filesystem($templatePath);
			$this->twig = new \Twig_Environment($loader);
		}
	}

	/**
	 * Returns the service container
	 *
	 * @return ServiceContainer
	 */
	protected function getServiceContainer() {
		return $this->module->getServiceContainer();
	}

	public function setData($data) {
		$this->data = $data;
	}

	abstract public function run(Request $request);
}