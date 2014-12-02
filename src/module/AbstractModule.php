<?php
namespace keeko\core\module;

use keeko\core\exceptions\ModuleException;
use keeko\core\application\Keeko;
use keeko\core\model\Action;
use keeko\core\model\Module;
use keeko\core\model\ActionQuery;
use keeko\core\application\AbstractApplication;
use keeko\core\package\PackageManager;
use keeko\core\auth\AuthManager;
use keeko\core\model\User;
use keeko\core\exceptions\PermissionDeniedException;
use keeko\core\service\ServiceContainer;
use keeko\core\preferences\Preferences;
use keeko\core\utils\TwigTrait;

abstract class AbstractModule {
	
	use TwigTrait;
	
	/** @var Module */
	protected $model;

	protected $actions;

	/** @var User */
	protected $user;
	
	/** @var ServiceContainer */
	protected $service;
	
	protected $preferences;

	public function __construct(Module $module, ServiceContainer $service) {
		$this->model = $module;
		$this->service = $service;
		
		$packageManager = $service->getPackageManager();
		$this->package = $packageManager->getModulePackage($module->getName());
		
		$this->loadActions();
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
	 * Returns the module model
	 *
	 * @return Module
	 */
	public function getModel() {
		return $this->model;
	}
	
	public function getName() {
		return $this->model->getName();
	}
	
	public function getCanonicalName() {
		return str_replace('/', '.', $this->getName());
	}
	
	public function getPath() {
		return sprintf('%s/%s/', KEEKO_PATH_MODULES, $this->model->getName());
	}
	
	/**
	 * Returns the module's preferences
	 *
	 * @return Preferences
	 */
	public function getPreferences() {
		if ($this->preferences === null) {
			$this->preferences = $this->service->getPreferenceLoader()->getModulePreferences($this->model->getId());
		}
		
		return $this->preferences();
	}

	private function loadActions() {
		$extra = $this->package->getExtra();
		
		if (isset($extra['keeko']) && isset($extra['keeko']['module']) && isset($extra['keeko']['module']['actions'])) {
			$this->actions = $extra['keeko']['module']['actions'];
		}
	}

	/**
	 * Loads the given action
	 *
	 * @param Action|string $name
	 * @param string $response the response type (e.g. html, json, ...)
	 * @return AbstractAction
	 */
	public function loadAction($nameOrAction, $response) {
		if ($nameOrAction instanceof Action) {
			$action = $nameOrAction;
		} else {
			$action = ActionQuery::create()->filterByModule($this->model)->findOneByName($nameOrAction);
			
			if ($action === null) {
				throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $nameOrAction, $this->model->getName()));
			}
		}
		
		$name = $action->getName();
		
		// check permission
		if (!$this->service->getFirewall()->canAccessAction($action)) {
			throw new PermissionDeniedException(sprintf('Can\'t access Action (%s) in Module (%s)', $name, $this->model->getName()));
		}
		
		// check if a response is given
		if (!(isset($this->actions[$name]) && isset($this->actions[$name]['response']) && isset($this->actions[$name]['response'][$response]))) {
			throw new ModuleException(sprintf('No Response (%s) given for Action (%s) in Module (%s)', $response, $name, $this->model->getName()));
		}
		$responseClass = $this->actions[$name]['response'][$response];
		
		if (!class_exists($responseClass)) {
			throw new ModuleException(sprintf('Response (%s) not found in Module (%s)', $responseClass, $this->model->getName()));
		}
		$response = new $responseClass($this, $response);
		
		// gets the action class
		$className = $action->getClassName();
		
		if (!class_exists($className)) {
			throw new ModuleException(sprintf('Action (%s) not found in Module (%s)', $className, $this->model->getName()));
		}
		
		$class = new $className($action, $this, $response);
		
		// l10n
		$app = $this->getServiceContainer()->getApplication();
		$lang = $app->getLocalization()->getLanguage()->getAlpha2();
		$country = $app->getLocalization()->getCountry()->getAlpha2();
		$l10n = $this->getPath() . 'l10n/';
		$locale = $lang . '_' . $country;
		
		// load module l10n
		$this->addL10nFile('module', $l10n, $lang, $locale, $class);
		
		// load additional l10n files
		if (isset($this->actions[$name]['l10n'])) {
			foreach ($this->actions[$name]['l10n'] as $file) {
				$this->addL10nFile($file, $l10n, $lang, $locale, $class);
			}
		}
		
		// load action l10n
		$this->addL10nFile(sprintf('actions/%s', $name), $l10n, $lang, $locale, $class);

		return $class;
	}
	
	private function addL10nFile($file, $dir, $lang, $locale, $class) {
		$translator = $this->getServiceContainer()->getTranslator();
		$langPath = sprintf('%s%s/%s.json', $dir, $lang, $file);
		$localePath = sprintf('%s%s/%s.json', $dir, $locale, $file);
		
		if (file_exists($langPath)) {
			
			$translator->addResource('json', $langPath, $lang, $class->getCanonicalName());
		}
		
		if (file_exists($localePath)) {
			$translator->addResource('json', $langPath, $locale, $class->getCanonicalName());
		}
	}
	
	public function getTwig() {
		return $this->getRawTwig($this->getPath() . 'templates/');
	}

	abstract public function install();

	abstract public function uninstall();

	abstract public function update($from, $to);
}
