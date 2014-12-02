<?php
namespace keeko\core\installer;

use keeko\core\module\ModuleManager;
use keeko\core\model\Localization;
use keeko\core\model\Module;
use keeko\core\model\ApplicationUri;
use Propel\Runtime\Propel;
use keeko\core\model\Application;
use Composer\IO\IOInterface;
use Composer\Composer;
use keeko\core\package\PackageManager;
use Composer\IO\NullIO;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\model\User;
use keeko\core\model\Group;
use keeko\core\model\Preference;
use keeko\core\service\ServiceContainer;
use keeko\core\preferences\SystemPreferences;
use keeko\core\model\Map\UserTableMap;
use keeko\core\model\UserQuery;

class KeekoInstaller {

	private $io;
	private $packageManager;
	private $appInstaller;
	private $moduleInstaller;
	private $moduleManager;
	
	private $localization;
	
	public function __construct(IOInterface $io = null) {
// 		// bootstrap
// 		if (!defined('KEEKO_PATH')) {
// 			require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
// 		}
		$this->io = $io ?: new NullIO();
	}
	
	private function initialize() {
		$service = new ServiceContainer();
		$this->packageManager = $service->getPackageManager();
		$this->appInstaller = new AppInstaller();
		$this->moduleInstaller = new ModuleInstaller();
		$this->moduleManager = $service->getModuleManager();
		
		// en_US
		$this->localization = new Localization();
		$this->localization->setLanguageId(1824); // de: 1546
		$this->localization->setCountryIsoNr(840); // ger: 276
		$this->localization->setIsDefault(true);
		$this->localization->save();
	}
	
	public function install() {
		$this->installStaticData();
		$this->initialize();
		$this->installGroupsAndUsers();
		$this->installKeeko();
	}
	
	/**
	 *
	 * @return Localization
	 */
	public function getLocalization() {
		return $this->localization;
	}
	
	public function installGroupsAndUsers() {
		$guestGroup = new Group();
		$guestGroup->setName('Guest');
		$guestGroup->setIsGuest(true);
		$guestGroup->save();
		
		$userGroup = new Group();
		$userGroup->setName('Users');
		$userGroup->setIsDefault(true);
		$userGroup->save();
		
		$adminGroup = new Group();
		$adminGroup->setName('Administrators');
		$adminGroup->save();

		
		$con = Propel::getConnection();
		$adapter = Propel::getAdapter();
		
		// guest
		$guest = new User();
		$guest->setDisplayName('Guest');
		$guest->save();
		
		$stmt = $con->prepare(sprintf('UPDATE %s SET id = -1 WHERE ID = 1', $adapter->quoteIdentifierTable(UserTableMap::TABLE_NAME)));
		$stmt->execute();
		
		// root
		$root = new User();
		$root->setDisplayName('root');
		$root->setLoginName('root');
		$root->setPassword(password_hash('root', PASSWORD_BCRYPT));
		$root->save();

		$stmt = $con->prepare(sprintf('UPDATE %s SET id = 0 WHERE ID = 2', $adapter->quoteIdentifierTable(UserTableMap::TABLE_NAME)));
		$stmt->execute();
		
		$root = UserQuery::create()->findOneById(0);
		$root->addGroup($userGroup);
		$root->addGroup($adminGroup);
		$root->save();
		
		// @TODO: Cross-SQL-Server routine wanted!!
		$stmt = $con->prepare(sprintf('ALTER TABLE %s AUTO_INCREMENT = 1', $adapter->quoteIdentifierTable(UserTableMap::TABLE_NAME)));
		$stmt->execute();
		
	}

	public function installKeeko() {
		// 1) apps
		
		// api
		$apiApp = $this->installApp('keeko/api-app');
		$apiUrl = $this->setAppUrl($apiApp, '/api/');
		
		// developer
		$developerApp = $this->installApp('keeko/developer-app');
		$this->setAppUrl($developerApp, '/developer/');
		
		// website
		$websiteApp = $this->installApp('keeko/website-app');
		$this->setAppUrl($websiteApp, '/');
		
		// 2) preferences
		$pref = new Preference();
		$pref->setKey(SystemPreferences::PLATTFORM_NAME);
		$pref->setValue('Keeko');
		$pref->save();
		
		$pref = new Preference();
		$pref->setKey(SystemPreferences::API_URL);
		$pref->setValue($apiUrl);
		$pref->save();

		// 3) modules
		$this->installModule('keeko/user');
		$this->activateModule('keeko/user');
		
		$this->installModule('keeko/group');
		$this->activateModule('keeko/group');
		
		$this->installModule('keeko/auth');
		$this->activateModule('keeko/auth');
	}
	
	public function installApp($packageName) {
		$package = $this->packageManager->getApplicationPackage($packageName);
		return $this->appInstaller->install($this->io, $package);
	}
	
	public function setAppUrl(Application $app, $path) {
		$request = Request::createFromGlobals();
		$base = $request->getBasePath();
		
		$uri = new ApplicationUri();
		$uri->setApplication($app);
		$uri->setLocalization($this->getLocalization());
		$uri->setHttphost($request->getHost());
		$uri->setBasepath($base . $path);
		$uri->setSecure($request->isSecure());
		$uri->save();
		
		return 'http' . ($request->isSecure() ? 's' : '') . '://' . $request->getHost() . $base . $path;
	}
	
	public function installModule($packageName) {
		$modulePackage = $this->packageManager->getModulePackage($packageName);
		if ($modulePackage) {
			$this->moduleInstaller->install($this->io, $modulePackage);
		}
	}
	
	public function activateModule($packageName) {
		$this->moduleManager->activate($packageName);
	}

	private function installStaticData() {
		$files = [
			'sql/keeko.sql',
			'data/static-data.sql'
		];
		$con = Propel::getConnection();
		
		foreach ($files as $file) {
			$path = KEEKO_PATH . '/core/database/' . $file;
			
			if (file_exists($path)) {
				$sql = file_get_contents($path);
				
				try {
					$stmt = $con->prepare($sql);
					$stmt->execute();
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
			}
		}
	}
}