<?php
namespace keeko\core\utils;

use Symfony\Component\HttpFoundation\Request;
use keeko\core\service\ServiceContainer;
use Symfony\Component\HttpFoundation\Response;

trait TwigRenderTrait {
	
	/**
	 * @return ServiceContainer
	 */
	abstract public function getServiceContainer();
	
	/**
	 * @return \Twig_Environment
	 */
	abstract protected function getTwig();
	
	private function getGlobalTwigVariables() {
		$request = Request::createFromGlobals();
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$user = $this->getServiceContainer()->getAuthManager()->getUser();
		$app = $this->getServiceContainer()->getApplication();
		return [
			'global' => [
				'plattform_name' => $prefs->getPlattformName(),
				'locations' => [
					'uri' => $request->getUri(),
					'root_url' => $app->getRootUrl(),
					'app_url' => $app->getAppUrl(),
					'app_path' => $app->getAppPath(),
					'app_root' => sprintf('%s/_keeko/apps/%s', $app->getRootUrl(), $app->getModel()->getName()),
					'destination' => $app->getDestinationPath(),
					'target' => $app->getTargetPath(),
					'tail' => $app->getTailPath()
				]
			],
			'user' => $user
		];
	}

	protected function render($name, $variables = []) {
		return new Response($this->getTwig()->render($name,
			array_merge($this->getGlobalTwigVariables(), $variables)));
	}
}