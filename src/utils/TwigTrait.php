<?php
namespace keeko\core\utils;

use keeko\core\service\ServiceContainer;

trait TwigTrait {
	
	private $twig;
	
	/**
	 * @return ServiceContainer
	 */
	abstract public function getServiceContainer();

	protected function getRawTwig($templatePath) {
		if ($this->twig === null) {
			$loader = new \Twig_Loader_Filesystem($templatePath);
			$this->twig = new \Twig_Environment($loader);
			
// 			$translator = $this->getServiceContainer()->getTranslator();
			
// 			// translator function
// 			$trans = function($key, $params = [], $domain = null) use ($translator) {
// 				return $translator->trans($key, $params, $domain);
// 			};
// 			$this->twig->addFunction(new \Twig_SimpleFunction('t', $trans));

			// firewall
			$firewall = $this->getServiceContainer()->getFirewall();
			$this->twig->addFunction(new \Twig_SimpleFunction('hasPermission', function ($module, $action) use ($firewall) {
				return $firewall->hasPermission($module, $action);
			}));
		}
		
		return $this->twig;
	}
}