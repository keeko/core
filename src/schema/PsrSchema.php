<?php
namespace keeko\core\schema;

use phootwork\lang\Arrayable;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;

class PsrSchema implements Arrayable {

	/** @var Map<string, string> */
	private $namespaces;
	
	public function __construct($contents = []) {
		$this->parse($contents);
	}

	private function parse($contents) {
		$this->namespaces = CollectionUtils::fromCollection($contents);
	}

	public function toArray() {
		return $this->namespaces->toArray();
	}
	
	public function setPath($namespace, $path) {
		$this->namespaces->set($namespace, $path);
	}

	/**
	 * Returns the path for the given namespace or null if the namespace doesn't exist
	 *
	 * @param string $namespace
	 * @return string|null
	 */
	public function getPath($namespace) {
		return $this->namespaces->get($namespace);
	}
	
	/**
	 * Returns the namespace for the given path or false if the path cannot be found
	 *
	 * @param string $path
	 * @return stirng the namespace
	 */
	public function getNamespace($path) {
		return $this->namespaces->getKey($path);
	}
	
	public function hasNamespace($namespace) {
		return $this->namespaces->has($namespace);
	}
	
	public function isEmpty() {
		return $this->namespaces->isEmpty();
	}

}
