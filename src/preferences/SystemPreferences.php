<?php
namespace keeko\core\preferences;

use keeko\core\preferences\Preferences;

class SystemPreferences extends Preferences {

	const PLATTFORM_NAME = 'plattform_name';
	const API_URL = 'api_url';
	
	public function getPlattformName() {
		return $this->get(self::PLATTFORM_NAME);
	}
	
	public function getApiUrl() {
		return $this->get(self::API_URL);
	}
}