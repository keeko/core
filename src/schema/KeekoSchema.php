<?php
namespace keeko\core\schema;

use phootwork\lang\Arrayable;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;

class KeekoSchema implements Arrayable {
	
	/** @var AppSchema */
	private $app;
	
	/** @var ModuleSchema */
	private $module;
	
	public function __construct($contents = []) {
		$this->parse($contents);
	}
	
	private function parse($contents) {
		/* @var $data Map */
		$data = CollectionUtils::fromCollection($contents);
	
		if ($data->has('app')) {
			$this->app = new AppSchema($data->get('app'));
		}
		
		if ($data->has('module')) {
			$this->module = new ModuleSchema($data->get('module'));
		}
	}
	
	public function toArray() {
		if ($this->app !== null) {
			return ['app' => $this->app->toArray()];
		}
		
		if ($this->module !== null) {
			return ['module' => $this->module->toArray()];
		}
		
		return [];
	}
	
	/**
	 * @return boolean
	 */
	public function isApp() {
		return $this->app !== null;
	}
	
	/**
	 * @return boolean
	 */
	public function isModule() {
		return $this->module !== null;
	}
	
	/**
	 *
	 * @return ModuleSchema
	 */
	public function getModule() {
		return $this->module;
	}
	
	/**
	 *
	 * @return AppSchema
	 */
	public function getApp() {
		return $this->app;
	}
	
}

