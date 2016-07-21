<?php
namespace keeko\core\domain;

use keeko\core\domain\base\UserDomainTrait;
use keeko\core\model\GroupQuery;
use keeko\core\model\User;
use keeko\core\validator\UserRegistrationValidator;
use keeko\core\validator\UserValidator;
use keeko\framework\foundation\AbstractDomain;
use keeko\framework\preferences\SystemPreferences;
use keeko\framework\validator\ValidatorInterface;

/**
 */
class UserDomain extends AbstractDomain {

	use UserDomainTrait;

	/**
	 * Returns the validator for users
	 *
	 * @param User $user
	 * @return ValidatorInterface
	 */
	protected function getValidator($user) {
		if ($user->isNew()) {
			return new UserRegistrationValidator($this->getServiceContainer());
		} else {
			return new UserValidator($this->getServiceContainer());
		}
	}

	/**
	 * @param User $user
	 */
	protected function postCreate(User $user) {
		$userGroup = GroupQuery::create()->filterByIsDefault(true)->findOne();
		if ($userGroup) {
		    $user->addGroup($userGroup);
		    $user->save();
		}
	}
	
	protected function preSave(User $user) {
		$this->normalizeNames($user);
		$this->updateDisplayName($user);
	}
	
	protected function normalizeNames(User $user) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		
		$user->setGivenName($this->normalizeName($user->getGivenName(), $prefs->getUserNormalizeGivenName()));
		$user->setFamilyName($this->normalizeName($user->getFamilyName(), $prefs->getUserNormalizeFamilyName()));
	}
	
	private function normalizeName($name, $how) {
		$name = trim($name);
		switch ($how) {
			case SystemPreferences::VALUE_NONE:
				return $name;
	
			case SystemPreferences::NORMALIZE_TITLECASE:
				$words = [];
				$ws = [];
				preg_match_all('/[^.\s-]+/', $name, $words);
				preg_match_all('/[.\s-]+/', $name, $ws);
	
				$len = count($words[0]) - 1;
				$result = '';
				foreach ($words[0] as $i => $word) {
					$result .= ucwords(strtolower($word));
	
					if ($i < $len) {
						$result .= $ws[0][$i];
					}
				}
	
				return $result;
	
			case SystemPreferences::NORMALIZE_UPPERCASE:
				return strtoupper($name);
	
			case SystemPreferences::NORMALIZE_LOWERCASE:
				return strtolower($name);
		}
	}
	
	protected function updateDisplayName(User $user) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();

		$choice = $prefs->getUserDisplayName();
		if ($prefs->getUserDisplayName() == SystemPreferences::DISPLAY_USERSELECT) {
			$choice = $user->getDisplayNameUserSelect();
		}
	
		switch ($choice) {
			case SystemPreferences::DISPLAY_GIVENFAMILYNAME:
				$user->setDisplayName($user->getGivenName() . ' ' . $user->getFamilyName());
				break;
	
			case SystemPreferences::DISPLAY_FAMILYGIVENNAME:
				$user->setDisplayName($user->getFamilyName() . ' ' . $user->getGivenName());
				break;
	
			case SystemPreferences::DISPLAY_NICKNAME:
				$user->setDisplayName($user->getNickName());
				break;
	
			case SystemPreferences::DISPLAY_USERNAME:
				$user->setDisplayName($user->getUserName());
				break;
		}
	}
}
