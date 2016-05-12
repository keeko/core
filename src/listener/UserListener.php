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