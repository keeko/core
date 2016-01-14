<?php
namespace keeko\core\preferences;

use keeko\core\preferences\Preferences;

class SystemPreferences extends Preferences {

	const VERSION = 'version';
	const PLATTFORM_NAME = 'plattform_name';
	const API_URL = 'api_url';
	const ROOT_URL = 'root_url';
	
	/**
	 * Returns the plattforms name
	 *
	 * @return string
	 */
	public function getPlattformName() {
		return $this->get(self::PLATTFORM_NAME);
	}
	
	/**
	 * Returns the url to the public API
	 *
	 * @return string
	 */
	public function getApiUrl() {
		return $this->get(self::API_URL);
	}
	
	/**
	 * Returns the plattform version (keeko/core)
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->get(self::VERSION);
	}
	
	/**
	 * Returns the root url for the installed plattform
	 *
	 * @return string
	 */
	public function getRootUrl() {
		return $this->get(self::ROOT_URL);
	}
}