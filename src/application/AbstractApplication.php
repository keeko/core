<?php
namespace keeko\core\application;

use keeko\core\model\Application;
use keeko\core\model\Localization;
use keeko\core\module\ModuleManager;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\package\PackageManager;
use keeko\core\auth\AuthManager;
use keeko\core\service\ServiceContainer;

abstract class AbstractApplication {

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

	/**
	 * Creates a new Keeko Application
	 */
	public function __construct(Application $model) {
		$this->model = $model;
		$this->service = new ServiceContainer($this);
	}
	
	/**
	 * Returns the service container
	 *
	 * @return ServiceContainer
	 */
	public function getServiceContainer() {
		return $this->service;
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

	abstract public function run(Request $request, $path);
}