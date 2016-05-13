<?php
namespace keeko\core\validator;

use keeko\framework\validator\ModelValidator;
use keeko\framework\preferences\SystemPreferences;

class UserValidator extends ModelValidator {
	
	protected function getValidations() {
		$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
		
		$validations = [];
		
		if ($prefs->getUserLogin() != SystemPreferences::LOGIN_EMAIL) {
			$validations['user_name'] = [
				['constraint' => 'notnull'],
				['constraint' => 'unique-username']
			];
		}
		
		if ($prefs->getUserEmail() || $prefs->getUserLogin() != SystemPreferences::LOGIN_USERNAME) {
			$validations['email'] = [['constraint' => 'email']];
			if ($prefs->getUserLogin() != SystemPreferences::LOGIN_USERNAME) {
				$validations['email'][] = ['constraint' => 'unique-email'];
			}
		}

		if ($prefs->getUserNames() == SystemPreferences::VALUE_REQUIRED) {
			$validations['given_name'] = [['constraint' => 'notnull']];
			$validations['family_name'] = [['constraint' => 'notnull']];
		}
		
		if ($prefs->getUserBirth() == SystemPreferences::VALUE_REQUIRED) {
			$validations['birth'] = [['constraint' => 'required']];
		}
		
		if ($prefs->getUserSex() == SystemPreferences::VALUE_REQUIRED) {
			$validations['sex'] = [['constraint' => 'choice', 'choices' => [0, 1]]];
		}
		
		return $validations;
	}
	
	protected function getCustomConstraints() {
		return [
			'unique-username' => 'keeko\core\validator\constraints\UniqueUsername',
			'unique-email' => 'keeko\core\validator\constraints\UniqueEmail'
		];
	}
}