<?php
namespace keeko\core\validator;

use keeko\framework\preferences\SystemPreferences;

class UserRegistrationValidator extends UserValidator {
	
	protected function getValidations() {
		$validations = parent::getValidations();
		
		$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
		
		if ($prefs->getUserLogin() != SystemPreferences::LOGIN_EMAIL) {
			$validations['user-name'] = [
				['constraint' => 'notnull'],
				['constraint' => 'unique-username']
			];
		}
		
		return $validations;
	}
}