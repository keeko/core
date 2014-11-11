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

	protected $prefix;

	protected $destination;

	protected $root;
	
	protected $base;
	
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

	public function setPrefix($prefix) {
		$this->prefix = $prefix;
		$this->updateBase();
	}

	public function setDestination($destination) {
		$this->destination = $destination;
	}

	public function setRoot($root) {
		$this->root = $root;
		$this->updateBase();
	}
	
	private function updateBase() {
		$this->base = $this->root . $this->prefix;
	}
	
	public function getPlattformUrl() {
		return $this->root;
	}
	
	public function getDestinationUrl() {
		return $this->destination;
	}
	
	public function getAppUrl() {
		return $this->base;
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