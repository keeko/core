<?php
namespace keeko\core\utils;

use keeko\core\service\ServiceContainer;

trait TwigTrait {
	
	/**
	 * @return ServiceContainer
	 */
	abstract public function getServiceContainer();

	protected function getRawTwig($templatePath) {
		$loader = new \Twig_Loader_Filesystem($templatePath);
		$twig = new \Twig_Environment($loader);
		
		$trans = $this->getServiceContainer()->getTranslator();
		
		$transFn = function($key, $params = [], $domain = null) use ($trans) {
			return $trans->trans($key, $params, $domain);
		};
		$twig->addFunction('trans', new \Twig_SimpleFunction('trans', $transFn));
		
		$transchoiceFn = function($key, $number, $params = [], $domain = null) use ($trans) {
			return $trans->transChoice($key, $number, $params, $domain);
		};
		$twig->addFunction('transchoice', new \Twig_SimpleFunction('transchoice', $transchoiceFn));
		
		return $twig;
	}
}