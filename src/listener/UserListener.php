<?php
namespace keeko\core\listener;

use keeko\framework\service\ServiceContainer;
use keeko\framework\events\KeekoEventListenerInterface;
use keeko\core\event\UserEvent;
use keeko\framework\preferences\SystemPreferences;

class UserListener implements KeekoEventListenerInterface {
	
	/** @var ServiceContainer */
	private $service;
	
	public function setServiceContainer(ServiceContainer $service) {
		$this->service = $service;
	}
	
	public function normalizeNames(UserEvent $event) {
		$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
		$user = $event->getUser();
		
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
	
	public function updateDisplayName(UserEvent $event) {
		$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
		$user = $event->getUser();
		
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