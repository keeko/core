<?php
namespace keeko\core\schema;

use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;

class CodegenSchema extends RootSchema {
	
	/** @var Map */
	private $data;
	
	/** @var Map */
	private $models;
	
	public function __construct($contents = []) {
		$this->parse($contents);
	}
	
	private function parse($contents) {
		$this->data = CollectionUtils::toMap($contents);
		
		$this->models = $this->data->get('models', new Map());
	}
	
	private function getArray($modelName, $io, $section) {
		if ($this->models->has($modelName)
				&& $this->models->get($modelName)->has($io)
				&& $this->models->get($modelName)->get($io)->has($section)) {
			return $this->models->get($modelName)->get($io)->get($section)->toArray();
		}

		return [];
	}
	
	public function getWriteConversion($modelName) {
		return $this->getArray($modelName, 'write', 'conversion');
	}
	
	public function getWriteFilter($modelName) {
		return $this->getArray($modelName, 'write', 'filter');
	}
	
	public function getReadFilter($modelName) {
		return $this->getArray($modelName, 'read', 'filter');
	}
}
