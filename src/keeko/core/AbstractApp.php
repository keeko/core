<?php
namespace keeko\core;

use Symfony\Component\HttpFoundation\Request;

use keeko\entities\App;

use Composer\Autoload\ClassLoader;

use keeko\routing\ApplicationRouter;

abstract class AbstractApp {

	protected $appRouter;

	protected $classLoader;

	protected $app;

	protected $root;

	public function __construct(App $app, ApplicationRouter $appRouter, ClassLoader $classLoader) {
		$this->app = $app;
		$this->appRouter = $appRouter;
		$this->classLoader = $classLoader;
		$this->root = KEEKO_PATH_APPS . DIRECTORY_SEPARATOR . $app->getUnixname();
	}

	public abstract function run(Request $req);

	/**
	 *
	 * @return ClassLoader
	 */
	public function getClassLoader() {
		return $this->classLoader;
	}

	/**
	 *
	 * @return AppRouter
	 */
	public function getAppRouter() {
		return $this->appRouter;
	}

	/**
	 *
	 * @param ClassLoader $loader
	 */
	public function setClassLoader(ClassLoader $loader) {
		$this->classLoader = $loader;
	}

	/**
	 *
	 * @param AppRouter $router
	 */
	public function setAppRouter(ApplicationRouter $router) {
		$this->appRouter = $router;
	}
}
