<?php
namespace keeko\core\application;

use Symfony\Component\HttpFoundation\Request;
use keeko\core\entities\Localization;

interface ApplicationInterface {

	public function install();
	
	public function setLocalization(Localization $localization);
	
	public function run(Request $request, $destination, $params);

	public static function getParams();

}