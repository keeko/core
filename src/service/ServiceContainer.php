<?php
namespace keeko\core\service;

use keeko\core\package\PackageManager;
use keeko\core\application\AbstractApplication;
use keeko\core\module\ModuleManager;
use keeko\core\auth\AuthManager;
use keeko\core\preferences\PreferenceLoader;
use keeko\core\security\Firewall;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use keeko\core\package\Runner;

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
	
	/** @var KeekoTranslator */
	private $translator;
	
	/** @var Runner */
	private $runner;
	
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
	
	/**
	 * Returns the keeko translation service
	 *
	 * @return KeekoTranslator
	 */
	public function getTranslator() {
		if ($this->translator === null) {
			$app = $this->getApplication();
			$lang = $app->getLocalization()->getLanguage()->getAlpha2();
			$this->translator = new KeekoTranslator($lang, new MessageSelector());
			$this->translator->addLoader('json', new JsonFileLoader());
			$this->translator->setFallbackLocales(['en']);
		}
		
		return $this->translator;
	}
	
	/**
	 * Returns a runner to run actions and applications
	 *
	 * @return Runner
	 */
	public function getRunner() {
		if ($this->runner === null) {
			$this->runner = new Runner();
		}
		
		return $this->runner;
	}
}