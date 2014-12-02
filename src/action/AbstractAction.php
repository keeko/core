<?php
namespace keeko\core\action;

use keeko\core\model\Action;
use keeko\core\module\AbstractModule;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\service\ServiceContainer;
use keeko\core\preferences\Preferences;
use keeko\core\package\RunnableInterface;

abstract class AbstractAction implements RunnableInterface {
	
	/** @var Action */
	protected $model;

	/** @var AbstractModule */
	protected $module;

	/** @var \Twig_Environment */
	protected $twig;

	/** @var array */
	protected $params = [];

	/** @var AbstractResponse */
	protected $response;
	
	private $domainBackup;

	public function __construct(Action $model, AbstractModule $module, AbstractResponse $response) {
		$this->model = $model;
		$this->response = $response;
		
		$this->module = $module;
		$templatePath = sprintf('%s/%s/templates/', KEEKO_PATH_MODULES, $module->getModel()->getName());
	
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
	
	public function getCanonicalName() {
		return $this->module->getCanonicalName() . '.' . $this->model->getName();
	}
	
	/**
	 * Returns the module's preferences
	 *
	 * @return Preferences
	 */
	protected function getPreferences() {
		return $this->module->getPreferences();
	}

	public function setParams($params) {
		$resolver = new OptionsResolver();
		$this->setDefaultParams($resolver);
		$this->params = $resolver->resolve($params);
	}

	protected function setDefaultParams(OptionsResolverInterface $resolver) {
		// does nothing, extend this method and provide functionality for your action
	}

	/**
	 * Returns the param
	 *
	 * @param string $name the param name
	 * @return mixed
	 */
	protected function getParam($name) {
		return $this->params[$name];
	}
	
	/**
	 * Returns the model for this action
	 *
	 * @return Action
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * Returns the associated module
	 *
	 * @return AbstractModule
	 */
	protected function getModule() {
		return $this->module;
	}
	
	public function beforeRun() {
		$translator = $this->getServiceContainer()->getTranslator();
		$this->domainBackup = $translator->getDomain();
		$translator->setDomain($this->getCanonicalName());
	}
	
	abstract public function run(Request $request);
	
	public function afterRun() {
		$translator = $this->getServiceContainer()->getTranslator();
		$translator->setDomain($this->domainBackup);
	}
}