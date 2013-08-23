<?php
namespace keeko\core\application;

use keeko\core\entities\Localization;
use keeko\core\entities\Application;
use keeko\core\routing\RouterInterface;
use keeko\core\routing\ApplicationRouter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\module\ModuleManager;

class KeekoApplication {

	/* @var $application Application */
	protected $application;
	
	/* @var $localization Localization */
	protected $localization;
	
	/* @var $router RouterInterface */
	protected $router;

	/* @var $moduleManager ModuleManager */
	protected $moduleManager;

	/**
	 * Creates a new Keeko Application
	 * 
	 */
	public function __construct() {
		$this->moduleManager = new ModuleManager();
	}	
	
	public function setApplication(Application $application) {
		$this->application = $application;
	}
	
	public function getApplication() {
		return $this->application;
	}
	
	public function setLocalization(Localization $localization) { 
		$this->localization = $localization;
	}
	
	public function getLocalization() {
		return $this->localization;
	}
	 
	/* (non-PHPdoc)
	 * @see \keeko\core\application\ApplicationInterface::run()
	 */
	public function run(Request $request, ApplicationRouter $appRouter) {
		// get params
		$params = $this->application->getExtraProperties(); // see: https://github.com/Carpe-Hora/ExtraPropertiesBehavior/issues/12
// 		$params = [];
// 		foreach ($this->application->getExtraProperties() as $key => $value) {
// 			$params[strtolower($key)] = $value;
// 		}
		$params['application'] = $this->application;
		$params['basepath'] = $appRouter->getPrefix();

		/* @var $router RouteMatcherInterface */
		$routing = $this->application->getRouter()->getClassname();
		$this->router = new $routing($params);
		
		$response = new Response('', 200, ['content-type' => 'text/html']);
		
		try {
			$handler = $this->router->getHandler();
			$match = $this->router->match($appRouter->getDestination());

			// get design
			$design = $this->application->getDesign();
			$designPath = KEEKO_PATH_DESIGNS . '/' . $design->getName() . '/';

			// get layout from handler
			$layout = $handler->getLayout($match);
			$layout = $layout === null ? 'main' : $layout;

			$layoutDir = $designPath . '/templates';

			// get contents
			$contents = $handler->getContents($match);

			$loader = new \Twig_Loader_Filesystem($layoutDir);
			$twig = new \Twig_Environment($loader);

			$root = str_replace($appRouter->getPrefix(), '', $appRouter->getUri()->getBasepath());
			$response->setContent($twig->render($layout . '.twig', array_merge($contents, [
				'paths' => [
					'prefix' => $appRouter->getPrefix(),
					'destination' => $appRouter->getDestination(),
					'base' => $appRouter->getUri()->getBasepath(),
					'root' => $root,
					'design' => $root . '/_keeko/designs/' . $design->getName()
				]
			])));

		} catch (ResourceNotFoundException $e) {
			$response->setStatusCode(404);
		} catch (MethodNotAllowedException $e) {
			$response->setStatusCode(405);
		}
		
		return $response;
	}

}