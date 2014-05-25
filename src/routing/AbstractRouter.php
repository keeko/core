<?php
namespace keeko\core\routing;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;

abstract class AbstractRouter {

	private $defaultOptions = [
		'param-separator' => '?',
		'param-delimiter' => '&',
		'param-true' => 'on',
		'param-false' => 'off'
	];

	private $requiredOptions = ['module', 'basepath'];

	private $optionalOptions = [];

	protected $options;

	/**
	 * The URL matcher
	 *
	 * @var UrlMatcher
	 */
	protected $matcher;

	/**
	 * The URL generator
	 *
	 * @var UrlGenerator
	 */
	protected $generator;

	public function __construct(array $options) {
		// options
		$resolver = new OptionsResolver();
		$this->setDefaultOptions($resolver);
		$this->options = $resolver->resolve($options);
	}

	protected function init(RouteCollection $routes) {
		$context = new RequestContext($this->options['basepath']);

		$this->matcher = new UrlMatcher($routes, $context);
		$this->generator = new UrlGenerator($routes, $context);
	}

	/**
	 * Returns the default options
	 *
	 * @return array
	 */
	protected function getDefaultOptions() {
		return [];
	}

	/**
	 * Returns the optional options
	 *
	 * @return array
	 */
	protected function getOptionalOptions() {
		return [];
	}

	/**
	 * Returns the required options
	 *
	 * @return array
	 */
	protected function getRequiredOptions() {
		return [];
	}

	private function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array_merge($this->defaultOptions, $this->getDefaultOptions()));
		$resolver->setOptional(array_merge($this->optionalOptions, $this->getOptionalOptions()));
		$resolver->setRequired(array_merge($this->requiredOptions, $this->getRequiredOptions()));
	}

	/**
	 * Unserializes Parameters
	 *
	 * @param string $params
	 * @return array the unserialized array
	 */
	public function unserializeParams($params) {
		$parts = explode($this->options['param-delimiter'], $params);
		$params = [];
		foreach ($parts as $part) {
			$kv = explode('=', $part);
			if ($kv[0] != '') {
				$params[$kv[0]] = count($kv) > 1
					? $kv[1] == $this->options['param-true']
						? true
						: ($kv[1] == $this->options['param-false'] ? false : $kv[1])
					: true;
			}
		}

		return $params;
	}

	/**
	 * Serializes Parameters
	 *
	 * @param array $params
	 * @return string the serialized params
	 */
	public function serializeParams($params) {
		$serialized = '';
		foreach ($params as $key => $val) {
			$serialized .= $key;
			if (is_bool($val) === true) {
				$serialized .= '=' . $val ? $this->options['param-true'] : $this->options['param-false'];
			} else if ($val != '') {
				$serialized .= '=' . $val;
			}
			$serialized .= $this->options['param-delimiter'];
		}
		return $serialized;
	}
}