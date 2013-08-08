<?php
namespace keeko\core\application;

use Symfony\Component\HttpFoundation\Request;
use keeko\core\entities\Localization;
use keeko\core\entities\Application;
use keeko\core\routing\ApplicationRouter;

interface ApplicationInterface {

// 	public function install();
	
	public function setApplication(Application $application);
	
	public function setLocalization(Localization $localization);
	
	public function run(Request $request, ApplicationRouter $router);

}