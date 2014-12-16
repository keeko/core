<?php
namespace keeko\core\application;

use keeko\core\model\Application;
use keeko\core\model\Localization;
use keeko\core\module\ModuleManager;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\package\PackageManager;
use keeko\core\auth\AuthManager;
use keeko\core\service\ServiceContainer;
use keeko\core\utils\TwigTrait;
use keeko\core\package\RunnableInterface;
use keeko\core\action\AbstractAction;
use keeko\core\utils\TwigRenderTrait;

abstract class AbstractApplication implements RunnableInterface {
	
	use TwigTrait;
	use TwigRenderTrait;

	/**
	 * @var Application
	 */
	protected $model;

	/**
	 * @var Localization
	 */
	protected $localization;

	protected $rootUrl;
	
	protected $appPath;

	protected $destinationPath;
	
	protected $appUrl;
	
	/** @var ServiceContainer */
	protected $service;
	
	/** @var Page */
	protected $page;

	/**
	 * Creates a new Keeko Application
	 */
	public function __construct(Application $model) {
		$this->model = $model;
		$this->service = new ServiceContainer($this);
		$this->page = new Page();
	}
	
	public function getCanonicalName() {
		return str_replace('/', '.', $this->model->getName());
	}
	
	/**
	 * Returns the service container
	 *
	 * @return ServiceContainer
	 */
	public function getServiceContainer() {
		return $this->service;
	}
	
	/**
	 * @return Page
	 */
	public function getPage() {
		return $this->page;
	}

	public function setModel(Application $model) {
		$this->model = $model;
	}
	
	/**
	 *
	 * @return Application
	 */
	public function getModel() {
		return $this->model;
	}

	public function setAppPath($prefix) {
		$this->appPath = $prefix;
		$this->updateAppUrl();
	}

	public function getAppPath() {
		return $this->appPath;
	}

	public function setRootUrl($root) {
		$this->rootUrl = $root;
		$this->updateAppUrl();
	}
	
	public function getRootUrl() {
		return $this->rootUrl;
	}
	
	private function updateAppUrl() {
		$this->appUrl = $this->rootUrl . $this->appPath;
	}
	
	public function getAppUrl() {
		return $this->appUrl;
	}
	
	public function setDestinationPath($destination) {
		$this->destinationPath = $destination;
	}

	public function getDestinationPath() {
		return $this->destinationPath;
	}
	
	public function getTargetPath() {
		return $this->destinationPath;
	}
	
	public function getTailPath() {
		return $this->destinationPath;
	}

	public function setLocalization(Localization $localization) {
		$this->localization = $localization;
	}

	/**
	 *
	 * @return Localization
	 */
	public function getLocalization() {
		return $this->localization;
	}
	
	public function getTwig() {
		return $this->getRawTwig(sprintf('%s/%s/templates/', KEEKO_PATH_APPS, $this->model->getName()));
	}
	
	protected function runAction(AbstractAction $action, Request $request) {
		$runner = $this->getServiceContainer()->getRunner();
		return $runner->run($action, $request);
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