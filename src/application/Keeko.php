<?php
namespace keeko\core\application;

use keeko\core\entities\Localization;
use keeko\core\entities\Application;
use keeko\core\routing\RouterInterface;
use keeko\core\routing\ApplicationRouter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\module\ModuleManager;

class Keeko {

	/**
	 * @var Application
	 */
	protected $application;

	/**
	 * @var Localization
	 */
	protected $localization;

	/**
	 * @var RouterInterface
	 */
	protected $router;

	/**
	 * @var ModuleManager
	 */
	protected $moduleManager;

	/**
	 * Creates a new Keeko Application
	 */
	public function __construct() {
		$this->moduleManager = new ModuleManager($this);
	}

	public function setEntity(Application $application) {
		$this->application = $application;
	}

	/**
	 *
	 * @return Application
	 */
	public function getEntity() {
		return $this->application;
	}

	public function setLocalization(Localization $localization) {
		$this->localization = $localization;
	}

	/**
	 *
	 * @return Localization
	 */
	public function getLocalization() {
		return $this->localization;
	}

	/**
	 *
	 * @return ModuleManager
	 */
	public function getModuleManager() {
		return $this->moduleManager;
	}

	public function run(Request $request, ApplicationRouter $appRouter) {
		// get params
		$params = $this->application->getParams();
		$params['application'] = $this->application;
		$params['basepath'] = $appRouter->getPrefix();
		
		$routing = $this->application->getRouter()->getClassname();
		$this->router = new $routing($params);
		
		$response = new Response('', 200, [
				'content-type' => 'text/html'
		]);
		
		try {
			$handler = $this->router->getHandler();
			$handler->setKeeko($this);
			$match = $this->router->match($appRouter->getDestination());
			
			// get design
			$design = $this->application->getDesign();
			$designPath = KEEKO_PATH_DESIGNS . '/' . $design->getName() . '/';
			
			// get layout from handler
			$layout = $handler->getLayout($match);
			$layout = $layout === null ? 'main' : $layout;
			
			$layoutDir = $designPath . '/templates';
			
			// get contents
			$blocks = $handler->getContents($match);
			
			$loader = new \Twig_Loader_Filesystem($layoutDir);
			$twig = new \Twig_Environment($loader);
			
			$root = str_replace($appRouter->getPrefix(), '', $appRouter->getUri()->getBasepath());
			$response->setContent($twig->render($layout . '.twig', [
					'blocks' => $blocks,
					'paths' => [
							'prefix' => $appRouter->getPrefix(),
							'destination' => $appRouter->getDestination(),
							'base' => $appRouter->getUri()->getBasepath(),
							'root' => $root,
							'design' => $root . '/_keeko/designs/' . $design->getName()
					]
			]));
		} catch (ResourceNotFoundException $e) {
			$response->setStatusCode(404);
		} catch (MethodNotAllowedException $e) {
			$response->setStatusCode(405);
		}
		
		return $response;
	}
}