<?php
namespace keeko\core\application;

use keeko\core\model\Application;
use keeko\core\model\Localization;
use keeko\core\module\ModuleManager;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\package\PackageManager;
use keeko\core\auth\AuthManager;

abstract class AbstractApplication {

	/**
	 * @var Application
	 */
	protected $model;

	/**
	 * @var Localization
	 */
	protected $localization;

	/**
	 * @var ModuleManager
	 */
	protected $moduleManager;

	/**
	 * @var PackageManager
	 */
	protected $packageManager;
	
	/** @var AuthManager */
	protected $authManager;

	protected $prefix;

	protected $base;

	protected $root;

	/**
	 * Creates a new Keeko Application
	 */
	public function __construct(Application $model) {
		$this->model = $model;
		$this->packageManager = new PackageManager();
		$this->moduleManager = new ModuleManager($this->packageManager, $this);
		$this->authManager = new AuthManager();
	}

	public function getPackageManager() {
		return $this->packageManager;
	}
	
	public function getAuthManager() {
		return $this->authManager;
	}

	public function setModel(Application $model) {
		$this->model = $model;
	}

	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}

	public function setBase($base) {
		$this->base = $base;
	}

	public function setRoot($root) {
		$this->root = $root;
	}

	/**
	 *
	 * @return Application
	 */
	public function getModel() {
		return $this->model;
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

	/**
	 *
	 * @return ModuleManager
	 */
	public function getModuleManager() {
		return $this->moduleManager;
	}

	abstract public function run(Request $request, $path);
}