<?php
namespace keeko\core\installer;

use keeko\core\entities\Router;
use keeko\core\entities\Localization;
use keeko\core\entities\Application;
use keeko\core\entities\ApplicationType;
use keeko\core\entities\ApplicationUri;
use keeko\core\entities\Design;
use keeko\core\entities\Layout;
use keeko\core\entities\Block;

class KeekoInstaller {

	public function __construct() {
		
		// bootstrap
		if (!defined('KEEKO_PATH')) {
			require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
		}
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
		
		// design
		$design = new Design();
		$design->setName('keeko/bootstrap-design');
		$design->setInstalledVersion('dev-master');
		
		$layout = new Layout();
		$layout->setName('main');
		$layout->setTitle('Default Layout');
		$layout->setDesign($design);
		
		$main = new Block();
		$main->setName('main');
		$main->setTitle('Main Content Block');
		$main->setLayout($layout);
		
		$menu = new Block();
		$menu->setName('menu');
		$menu->setTitle('Navigation');
		$menu->setLayout($layout);
		
		$design->save();
		
		
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
		$admin->setDesign($design);
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