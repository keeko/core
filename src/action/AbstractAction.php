<?php
namespace keeko\core\action;

use keeko\core\model\Action;
use keeko\core\module\AbstractModule;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractAction {

	const MAX_PER_PAGE = 50;
	
	/**
	 *
	 * @var Action
	 */
	protected $model;

	/**
	 *
	 * @var AbstractModule
	 */
	protected $module;

	/**
	 *
	 * @var \Twig_Environment
	 */
	protected $twig;

	protected $params = [];

	protected $response;

	public function __construct(Action $model, AbstractResponse $response) {
		$this->model = $model;
		$this->response = $response;
	}

	public function setParams($params) {
		$resolver = new OptionsResolver();
		$this->setDefaultParams($resolver);
		$this->params = $resolver->resolve($params);
	}

	protected function setDefaultParams(OptionsResolverInterface $resolver) {
		// does nothing, extend this method and provide functionality for your action
	}
	
	protected function getModule() {
		return $this->module;
	}

	protected function getParam($name) {
		return $this->params[$name];
	}

	public function setModel(Action $action) {
		$this->model = $action;
	}

	public function setModule(AbstractModule $module) {
		$this->module = $module;
		$templatePath = sprintf('%s/%s/templates/%s', KEEKO_PATH_MODULES, $module->getModel()->getName(), $module->getKeeko()->getEntity()->getApplicationType()->getName());
		
		$loader = new \Twig_Loader_Filesystem($templatePath);
		$this->twig = new \Twig_Environment($loader);
	}
	
	abstract public function run(Request $request);
}