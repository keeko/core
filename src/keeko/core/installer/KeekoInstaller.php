<?php
namespace keeko\core\installer;

use keeko\core\entities\Router;
use keeko\core\entities\Localization;
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationType;
use keeko\core\entities\ApplicationUri;

class KeekoInstaller {

	public function __construct() {
		
		// bootstrap
		require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
	}
	
	public function installKeeko() {
		$this->installStaticData();
		
		// routers
		$mar = new Router();
		$mar->setTitle('Module-Action-Router');
		$mar->setClassname('\\keeko\\core\\routing\\ModuleActionRouter');
		$mar->save();
		
		// localization
		// de
		$de = new Localization();
		$de->setLanguageId(1546);
		$de->setCountryIsoNr(276);
		$de->setIsDefault(true);
		$de->save();
		
		// apps
		// website
		$website = new ApplicationType();
		$website->setTitle('keeko/website-app');
		$website->setDescription('- description -');
		$website->setInstalledVersion('dev-master');
		$website->setClassname('\\keeko\\applications\\website\\WebsiteApplication');
		$website->save();
		
		// gateways
		// admin
		$admin = new Application();
		$admin->setTitle('Admin');
		$admin->setApplicationType($website);
		$admin->setRouter($mar);
		$admin->setProperty('module', 'admin');
		$admin->save();
		
		$uri = new ApplicationUri();
		$uri->setApplication($admin);
		$uri->setLocalization($de);
		$uri->setHttphost('localhost');
		$uri->setBasepath('/keeko/public/admin');
		$uri->save();
	}
	
	private function installStaticData() {
		$files = ['sql/schema.sql', 'data/static-data.sql'];
		$con = \Propel::getConnection();
		
		foreach ($files as $file) {
			$path = KEEKO_PATH . '/core/database/' . $file;
			
			if (file_exists($path)) {
				$sql = file_get_contents($path);
				
				$stmt = $con->prepare($sql);
				$stmt->execute();
			}
		} 
	}
}