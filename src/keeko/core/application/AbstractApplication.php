<?php
namespace keeko\core\application;

use keeko\core\application\ApplicationInterface;
use keeko\core\entities\Localization;
use keeko\core\entities\Application;
use keeko\core\routing\RouterInterface;
use keeko\core\routing\ApplicationRouter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApplication implements ApplicationInterface {

	/* @var $application Application */
	protected $application;
	
	/* @var $localization Localization */
	protected $localization;
	
	/* @var $router RouterInterface */
	protected $router; 
	
	public function setApplication(Application $application) {
		$this->application = $application;
	}
	
	public function setLocalization(Localization $localization) { 
		$this->localization = $localization;
	}
	 
	/* (non-PHPdoc)
	 * @see \keeko\core\application\ApplicationInterface::run()
	 */
	public function run(Request $request, ApplicationRouter $appRouter) {
		// get params
		$params = $this->application->getExtraProperties();
		$params['application'] = $this->application;
		$params['basepath'] = $appRouter->getPrefix();
		
		/* @var $router RouteMatcherInterface */
		$routing = $this->application->getRouter()->getClassname();
		$this->router = new $routing($params);
		
		$response = new Response('', 200, ['content-type' => 'text/html']);
		$response = $this->doRun($request, $response, $appRouter);
		
		$response->prepare($request);
		$response->send();
	}
	
	/**
	 * 
	 * @param Request $request
	 * @param Response $response
	 * @param ApplicationRouter $appRouter
	 * 
	 * @return Response
	 */
	abstract protected function doRun(Request $request, Response $response, ApplicationRouter $appRouter);

}