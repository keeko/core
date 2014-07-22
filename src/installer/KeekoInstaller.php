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
use keeko\core\model\GroupUser;

class KeekoInstaller {

	private $io;
	private $packageManager;
	private $appInstaller;
	private $moduleInstaller;
	private $moduleManager;
	
	public function __construct(IOInterface $io = null) {
// 		// bootstrap
// 		if (!defined('KEEKO_PATH')) {
// 			require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
// 		}
		
		$this->io = $io ?: new NullIO();
		$this->packageManager = new PackageManager();
		$this->appInstaller = new AppInstaller();
		$this->moduleInstaller = new ModuleInstaller();
		$this->moduleManager = new ModuleManager($this->packageManager);
	}
	
	public function install() {
		$this->installStaticData();
		$this->installGroupsAndUsers();
		$this->installKeeko();
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
		
		$root = new User();
		$root->setDisplayName('root');
		$root->setLoginName('root');
		$root->setPassword(password_hash('root', PASSWORD_BCRYPT));
		$root->save();
		$root->addGroup($userGroup);
		$root->addGroup($adminGroup);
		
		$guest = new User();
		$guest->setDisplayName('Guest');
		$guest->save();
		$guest->addGroup($guestGroup);
	}

	public function installKeeko() {
		$request = Request::createFromGlobals();
		$base = $request->getBasePath();

		// localization
		// de
		$de = new Localization();
		$de->setLanguageId(1546);
		$de->setCountryIsoNr(276);
		$de->setIsDefault(true);
		$de->save();
		
		// apps
		// -- api app
		$apiAppPackage = $this->packageManager->getApplicationPackage('keeko/api-app');
		$apiApp = $this->appInstaller->install($this->io, $apiAppPackage);
		
		$uri = new ApplicationUri();
		$uri->setApplication($apiApp);
		$uri->setLocalization($de);
		$uri->setHttphost($request->getHost());
		$uri->setBasepath($base . '/api/');
		$uri->setSecure($request->isSecure());
		$uri->save();
		
		$developerAppPackage = $this->packageManager->getApplicationPackage('keeko/developer-app');
		$developerApp = $this->appInstaller->install($this->io, $developerAppPackage);
		
		$uri = new ApplicationUri();
		$uri->setApplication($developerApp);
		$uri->setLocalization($de);
		$uri->setHttphost('localhost');
		$uri->setBasepath($base . '/developer/');
		$uri->setSecure($request->isSecure());
		$uri->save();
		
		// -- website app
		$websiteAppPackage = $this->packageManager->getApplicationPackage('keeko/website-app');
		$websiteApp = $this->appInstaller->install($this->io, $websiteAppPackage);
		
		$uri = new ApplicationUri();
		$uri->setApplication($websiteApp);
		$uri->setLocalization($de);
		$uri->setHttphost('localhost');
		$uri->setBasepath($base . '/');
		$uri->setSecure($request->isSecure());
		$uri->save();

		// modules
		$this->installModule('keeko/user');
		$this->activateModule('keeko/user');
		
		$this->installModule('keeko/group');
		$this->activateModule('keeko/group');
		
		$this->installModule('keeko/auth');
		$this->activateModule('keeko/auth');
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