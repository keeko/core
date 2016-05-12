<?php
namespace keeko\core\validator;

use keeko\framework\validator\ModelValidator;
use keeko\framework\preferences\SystemPreferences;

class UserValidator extends ModelValidator {
	
	protected function getValidations() {
		$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
		
		$validations = [];
		
		if ($prefs->getUserLogin() != SystemPreferences::LOGIN_EMAIL) {
			$validations[] = [
				'constraint' => 'notnull',
				'column' => 'user_name'
			];
		}
		
		if ($prefs->getUserEmail() || $prefs->getUserLogin() != SystemPreferences::LOGIN_USERNAME) {
			$validations[] = [
				'constraint' => 'email',
				'column' => 'email'
			];
		}

		if ($prefs->getUserNames()) {
			$validations[] = [
				'constraint' => 'notnull',
				'column' => 'given_name'
			];
			
			$validations[] = [
				'constraint' => 'notnull',
				'column' => 'family_name'
			];
		}
		
		if ($prefs->getUserBirth()) {
			$validations[] = [
				'constraint' => 'required',
				'column' => 'birth'
			];
		}
		
		if ($prefs->getUserSex()) {
			$validations[] = [
				'constraint' => 'choice',
				'column' => 'sex',
				'choices' => [0, 1]
			];
		}
		
		return $validations;
	}
}