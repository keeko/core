<?php
namespace keeko\core\preferences;

use keeko\core\model\PreferenceQuery;

class PreferenceLoader {

	private $preferences = [];
	
	public function __construct($preferences = null) {
		$preferences = PreferenceQuery::create()->find();
		foreach ($preferences as $preference) {
			$module = $preference->getModuleId() ?: 'system';
			if (!isset($this->preferences[$module])) {
				$this->preferences[$module] = [];
			}
			
			$this->preferences[$module][$preference->getKey()] = $preference->getValue();
		}
	}
	
	public function getSystemPreferences() {
		return new Preferences($this->preferences['system']);
	}
	
	public function getModulePreferences($moduleId) {
		if (isset($this->preferences[$moduleId])) {
			return new Preferences($this->preferences[$moduleId]);
		}
	}
	
}
