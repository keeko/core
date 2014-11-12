<?php
namespace keeko\core\service;

use keeko\core\package\PackageManager;
use keeko\core\application\AbstractApplication;
use keeko\core\module\ModuleManager;
use keeko\core\auth\AuthManager;
use keeko\core\preferences\PreferenceLoader;
use keeko\core\security\Firewall;

class ServiceContainer {

	/** @var PackageManager */
	private $packageManager;
	
	/** @var AbstractApplication */
	private $application;
	
	/** @var ModuleManager */
	private $moduleManager;
	
	/** @var AuthManager */
	private $authManager;
	
	/** @var PreferenceLoader */
	private $preferenceLoader;
	
	/** @var Firewall */
	private $firewall;
	
	public function __construct(AbstractApplication $application = null) {
		if ($application !== null) {
			$this->application = $application;
		}
	}
	
	/**
	 * Returns the active application
	 *
	 * @return AbstractApplication
	 */
	public function getApplication() {
		return $this->application;
	}
	
	/**
	 * Returns the package manager
	 *
	 * @return PackageManager
	 */
	public function getPackageManager() {
		if ($this->packageManager === null) {
			$this->packageManager = new PackageManager();
		}
		
		return $this->packageManager;
	}
	
	/**
	 * Returns the module manager
	 *
	 * @return ModuleManager
	 */
	public function getModuleManager() {
		if ($this->moduleManager === null) {
			$this->moduleManager = new ModuleManager($this);
		}
		
		return $this->moduleManager;
	}
	
	/**
	 * Returns the auth manager
	 *
	 * @return AuthManager
	 */
	public function getAuthManager() {
		if ($this->authManager === null) {
			$this->authManager = new AuthManager();
		}
	
		return $this->authManager;
	}
	
	/**
	 * Returns the preference loader
	 *
	 * @return PreferenceLoader
	 */
	public function getPreferenceLoader() {
		if ($this->preferenceLoader === null) {
			$this->preferenceLoader = new PreferenceLoader();
		}
		
		return $this->preferenceLoader;
	}
	
	/**
	 * Returns the firewall
	 *
	 * @return Firewall
	 */
	public function getFirewall() {
		if ($this->firewall === null) {
			$this->firewall = new Firewall($this);
		}
		
		return $this->firewall;
	}
}