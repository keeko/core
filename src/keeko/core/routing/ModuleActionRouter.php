<?php
namespace keeko\core\routing;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use keeko\core\handler\ModuleActionHandler;

class ModuleActionRouter implements RouterInterface {

	private $generator;
	private $matcher;
	private $options;

	public function __construct(array $options) {
		// options
		$resolver = new OptionsResolver();
		$this->setDefaultOptions($resolver);
		$this->options = $resolver->resolve($options);
		
		// routes
		$routes = new RouteCollection();

		$moduleRoute = new Route('/{module}', array('module' => $this->options['module']));
		$actionRoute = new Route('/{module}/{action}');
		$paramsRoute = new Route(sprintf('/{module}/{action}%s{params}', 
				$this->options['param-separator']));

		$routes->add('module', $moduleRoute);
		$routes->add('action', $actionRoute);
		$routes->add('params', $paramsRoute);
	
		$context = new RequestContext($this->options['basepath']);

		$this->matcher = new UrlMatcher($routes, $context);
		$this->generator = new UrlGenerator($routes, $context);
	}
	
	private function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults([
			'param-separator' => '?',
			'param-delimiter' => '&'
		]);
		$resolver->setOptional(['application']);
		$resolver->setRequired(['module', 'basepath']);
	}
	
	
	/* (non-PHPdoc)
	 * @see \keeko\core\routing\RouteWithHandlerInterface::getHandler()
	 */
	public function getHandler() {
		return new ModuleActionHandler();
	}


	/*
	 * (non-PHPdoc)
	 * @see \keeko\core\routing\RouteMatcherInterface::match()
	 */
	public function match($destination) {
		if ($destination == '') {
			$destination = '/';
		}

		$data = $this->matcher->match($destination);

		// read params
		if (array_key_exists('params', $data)) {
			$parts = explode($this->options['param-delimiter'], $data['params']);
			$params = array();
			foreach ($parts as $part) {
				$kv = explode('=', $part);
				if ($kv[0] != '') {
					$params[$kv[0]] = count($kv) > 1
						? $kv[1] == 'on' ? true : ($kv[1] == 'off' ? false : $kv[1])
						: true;
				}
			}
			$data['params'] = $params;
		}

		return $data;
	}

	public function generate($data) {

		// params route
		if (array_key_exists('params', $data)) {

			// stringify params
			$params = '';
			foreach ($data['params'] as $key => $val) {
				$params .= $key;
				if (is_bool($val) === true) {
					$params .= '=' . $val ? 'on' : 'off';
				} else if ($val != '') {
					$params .= '=' . $val;
				}
				$params .= $this->options['param-delimiter'];
			}
			$data['params'] = $data;
			return $this->generator->generate('params', $data);
		}

		// action route
		if (array_key_exists('action', $data)) {
			return $this->generator->generate('action', $data);
		}

		// module route
		if (array_key_exists('module', $data)) {
			return $this->generator->generate('module', $data);
		}
	}
}
