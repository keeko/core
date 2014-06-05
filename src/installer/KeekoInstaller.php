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

class KeekoInstaller {

	public function __construct() {
		// bootstrap
		if (!defined('KEEKO_PATH')) {
			require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
		}
	}

	public function installKeeko(IOInterface $io, Composer $composer) {
		$this->installStaticData();
		
		$packageManager = new PackageManager();
		$appInstaller = new AppInstaller();
		$moduleInstaller = new ModuleInstaller();
		$moduleManager = new ModuleManager($packageManager);
		
		// localization
		// de
		$de = new Localization();
		$de->setLanguageId(1546);
		$de->setCountryIsoNr(276);
		$de->setIsDefault(true);
		$de->save();
		
		// apps
		// -- api app
		$apiAppPackage = $packageManager->getApplicationPackage('keeko/api-app');
		$apiApp = $appInstaller->install($io, $apiAppPackage);
		
		$uri = new ApplicationUri();
		$uri->setApplication($apiApp);
		$uri->setLocalization($de);
		$uri->setHttphost('localhost');
		$uri->setBasepath('/keeko/public/api/');
		$uri->save();
		
		$developerAppPackage = $packageManager->getApplicationPackage('keeko/developer-app');
		$developerApp = $appInstaller->install($io, $developerAppPackage);
		
		$uri = new ApplicationUri();
		$uri->setApplication($developerApp);
		$uri->setLocalization($de);
		$uri->setHttphost('localhost');
		$uri->setBasepath('/keeko/public/developer/');
		$uri->save();
		
		// -- website app
		$websiteAppPackage = $packageManager->getApplicationPackage('keeko/website-app');
		$websiteApp = $appInstaller->install($io, $websiteAppPackage);
		
		$uri = new ApplicationUri();
		$uri->setApplication($websiteApp);
		$uri->setLocalization($de);
		$uri->setHttphost('localhost');
		$uri->setBasepath('/keeko/public/');
		$uri->save();
		
		// modules
		$userModulePackage = $packageManager->getModulePackage('keeko/user');
		if ($userModulePackage) {
			$moduleInstaller->install($io, $userModulePackage);
			$moduleManager->activate('keeko/user');
		}
		
		$groupModulePackage = $packageManager->getModulePackage('keeko/group');
		if ($groupModulePackage) {
			$moduleInstaller->install($io, $groupModulePackage);
			$moduleManager->activate('keeko/group');
		}
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