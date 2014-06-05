<?php
namespace keeko\core\action;

use keeko\core\module\AbstractModule;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractResponse {

	protected $data;

	protected $twig;

	protected $module;

	public function __construct(AbstractModule $module, $response) {
		$this->module = $module;
		$templatePath = sprintf('%s/%s/templates/%s', KEEKO_PATH_MODULES, $module->getModel()->getName(), $response);
		
		if (file_exists($templatePath)) {
			$loader = new \Twig_Loader_Filesystem($templatePath);
			$this->twig = new \Twig_Environment($loader);
		}
	}

	public function setData($data) {
		$this->data = $data;
	}

	abstract public function run(Request $request);
}